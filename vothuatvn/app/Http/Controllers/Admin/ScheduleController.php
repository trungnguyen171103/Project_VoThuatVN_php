<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Club;
use App\Models\ClassModel;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display all schedules (Class-based Card View)
     */
    public function index(Request $request)
    {
        $clubs = Club::active()->get();

        // Get all classes that have schedules
        $classes = ClassModel::with(['club', 'coach.user', 'schedules'])
            ->whereHas('schedules')
            ->where('status', 'active')
            ->get()
            ->map(function ($class) {
                // Get first schedule to determine typical time
                $firstSchedule = $class->schedules->first();

                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'class_code' => $class->class_code,
                    'club_name' => $class->club->name,
                    'coach_name' => $class->coach?->user?->name ?? 'Chưa phân công',
                    'start_date' => $class->start_date,
                    'end_date' => $class->end_date,
                    'status' => $class->status,
                    'schedule_time' => $firstSchedule ? substr($firstSchedule->start_time, 0, 5) . ' - ' . substr($firstSchedule->end_time, 0, 5) : 'Chưa có lịch',
                    'schedule_count' => $class->schedules->count(),
                ];
            });

        // Current week for modal default
        $currentWeek = Carbon::now()->startOfWeek()->format('Y-m-d');

        return view('admin.schedules.index', compact('classes', 'clubs', 'currentWeek'));
    }

    /**
     * Show form to create new schedule
     */
    public function create()
    {
        $clubs = Club::active()->get();
        return view('admin.schedules.create', compact('clubs'));
    }

    /**
     * Get classes for selected club
     */
    public function getClubClasses($clubId)
    {
        $classes = ClassModel::where('club_id', $clubId)
            ->where('status', 'active')
            ->with('coach.user')
            ->get();

        return response()->json($classes);
    }

    /**
     * Store new schedule
     */
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'class_id' => 'required|exists:classes,id',
            'days' => 'required|array|min:1',
            'days.*' => 'integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $class = ClassModel::findOrFail($request->class_id);

        // Define duration based on start/end time input
        $startTimeStr = $request->start_time;
        $endTimeStr = $request->end_time;

        $start = Carbon::parse($startTimeStr);
        $end = Carbon::parse($endTimeStr);
        $duration = $start->diffInMinutes($end);

        // Generate schedules from class start_date to end_date (or 3 months if no end_date)
        $currentDate = $class->start_date->copy();
        $limitDate = $class->end_date ? $class->end_date->copy() : $currentDate->copy()->addMonths(3);

        $scheduleCount = 0;
        $conflicts = [];

        while ($currentDate->lte($limitDate)) {
            // Check if current day of week is selected (0=Sun, 1=Mon, ...)
            if (in_array($currentDate->dayOfWeek, $request->days)) {
                $dateStr = $currentDate->format('Y-m-d');

                // Check conflict
                if (!$this->checkCoachConflict($class->coach_id, $dateStr, $startTimeStr, $endTimeStr, null)) {
                    Schedule::create([
                        'class_id' => $class->id,
                        'date' => $dateStr,
                        'day_of_week' => $currentDate->dayOfWeek,
                        'start_time' => $startTimeStr,
                        'end_time' => $endTimeStr,
                        'duration' => $duration,
                    ]);
                    $scheduleCount++;
                } else {
                    $conflicts[] = $currentDate->format('d/m/Y');
                }
            }
            $currentDate->addDay();
        }

        if ($scheduleCount === 0 && count($conflicts) > 0) {
            return back()->with('error', 'Không thể tạo lịch nào do trùng lịch HLV vào các ngày: ' . implode(', ', array_slice($conflicts, 0, 5)) . '...');
        }

        $message = "Đã tạo thành công $scheduleCount buổi học!";
        if (count($conflicts) > 0) {
            $message .= ' (Bỏ qua ' . count($conflicts) . ' buổi do trùng lịch)';
        }

        return redirect()->route('admin.schedules.index')->with('success', $message);
    }

    /**
     * Check for coach schedule conflicts
     */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        $class = ClassModel::findOrFail($request->class_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes(90);

        $hasConflict = $this->checkCoachConflict(
            $class->coach_id,
            $request->date,
            $startTime->format('H:i'),
            $endTime->format('H:i'),
            $request->schedule_id ?? null
        );

        return response()->json([
            'has_conflict' => $hasConflict,
            'message' => $hasConflict ? 'HLV đã có lịch dạy trùng giờ!' : 'Lịch dạy hợp lệ',
        ]);
    }

    /**
     * Helper method to check coach conflict
     */
    private function checkCoachConflict($coachId, $date, $startTime, $endTime, $excludeScheduleId = null)
    {
        $query = Schedule::whereHas('classModel', function ($q) use ($coachId) {
            $q->where('coach_id', $coachId);
        })
            ->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }

    /**
     * Get class schedules for a specific week (AJAX)
     */
    public function getClassWeekSchedules(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'week' => 'required|date',
        ]);

        $class = ClassModel::with(['club', 'coach.user'])->findOrFail($request->class_id);

        // Calculate week range
        $date = Carbon::parse($request->week);
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // Fetch schedules for this class in this week
        $schedules = Schedule::where('class_id', $request->class_id)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                $dayOfWeek = Carbon::parse($schedule->date)->dayOfWeek;
                $dayName = $dayOfWeek == 0 ? 'Chủ Nhật' : 'Thứ ' . ($dayOfWeek + 1);

                return [
                    'id' => $schedule->id,
                    'date' => Carbon::parse($schedule->date)->format('d/m/Y'),
                    'day_of_week' => $dayOfWeek,
                    'day_name' => $dayName,
                    'start_time' => substr($schedule->start_time, 0, 5),
                    'end_time' => substr($schedule->end_time, 0, 5),
                ];
            });

        return response()->json([
            'class' => [
                'name' => $class->name,
                'class_code' => $class->class_code,
                'club_name' => $class->club->name,
                'coach_name' => $class->coach?->user?->name ?? 'Chưa phân công',
            ],
            'schedules' => $schedules,
            'week_label' => 'Tuần từ ngày ' . $startOfWeek->format('d/m/Y') . ' đến ngày ' . $endOfWeek->format('d/m/Y'),
            'prev_week' => $startOfWeek->copy()->subWeek()->format('Y-m-d'),
            'next_week' => $startOfWeek->copy()->addWeek()->format('Y-m-d'),
        ]);
    }

    /**
     * Delete all schedules for a class
     */
    public function destroyClassSchedules($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $schedules = Schedule::where('class_id', $classId)->get();

        if ($schedules->isEmpty()) {
            return back()->with('error', 'Không tìm thấy lịch học nào!');
        }

        // Check if any schedule has attendance
        foreach ($schedules as $schedule) {
            if (Attendance::where('class_id', $classId)->where('date', $schedule->date)->exists()) {
                return back()->with('error', 'Không thể xoá lịch đã có điểm danh!');
            }
        }

        // Check if any schedule is in the past
        $now = Carbon::now();
        foreach ($schedules as $schedule) {
            $scheduleDateTime = Carbon::parse($schedule->date->format('Y-m-d') . ' ' . $schedule->end_time);
            if ($scheduleDateTime->lt($now)) {
                return back()->with('error', 'Không thể xoá lịch đã diễn ra!');
            }
        }

        // Delete all schedules
        Schedule::where('class_id', $classId)->delete();

        return back()->with('success', 'Đã xoá tất cả lịch học của lớp "' . $class->name . '"!');
    }

    /**
     * Delete schedule
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Safety checks
        if (Attendance::where('class_id', $schedule->class_id)->where('date', $schedule->date)->exists()) {
            return back()->with('error', 'Không thể xoá buổi học đã có điểm danh!');
        }

        $now = Carbon::now();
        $scheduleDateTime = Carbon::parse($schedule->date->format('Y-m-d') . ' ' . $schedule->end_time);
        if ($scheduleDateTime->lt($now)) {
            return back()->with('error', 'Không thể xoá buổi học đã diễn ra!');
        }

        $schedule->delete();

        return back()->with('success', 'Xóa lịch học thành công!');
    }
}
