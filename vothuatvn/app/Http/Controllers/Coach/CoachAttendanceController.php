<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachAttendanceController extends Controller
{
    /**
     * Display calendar view of teaching schedule
     */
    public function index()
    {
        $coach = Auth::user()->coach;

        // Get schedules for current month
        $schedules = Schedule::whereHas('classModel', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with('classModel.club')
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<=', now()->endOfMonth())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Group by date for calendar display
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return $schedule->date->format('Y-m-d');
        });

        return view('coach.attendance.index', compact('schedules', 'schedulesByDate'));
    }

    /**
     * Show attendance marking form for a specific session
     */
    public function show($scheduleId)
    {
        $coach = Auth::user()->coach;

        $schedule = Schedule::whereHas('classModel', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with('classModel.students', 'classModel.club')
            ->findOrFail($scheduleId);

        // Get existing attendance records for this class and date
        $existingAttendance = Attendance::where('class_id', $schedule->class_id)
            ->where('date', $schedule->date)
            ->pluck('status', 'student_id')
            ->toArray();

        return view('coach.attendance.mark', compact('schedule', 'existingAttendance'));
    }

    /**
     * Store attendance records
     */
    public function store(Request $request, $scheduleId)
    {
        $coach = Auth::user()->coach;

        $schedule = Schedule::whereHas('classModel', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with('classModel.students')
            ->findOrFail($scheduleId);

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,excused',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing attendance for this class and date
            Attendance::where('class_id', $schedule->class_id)
                ->where('date', $schedule->date)
                ->delete();

            // Create new attendance records
            foreach ($request->attendance as $studentId => $status) {
                Attendance::create([
                    'class_id' => $schedule->class_id,
                    'student_id' => $studentId,
                    'status' => $status,
                    'date' => $schedule->date,
                    'notes' => $request->notes,
                ]);
            }

            DB::commit();
            return redirect()->route('coach.attendance.index')
                ->with('success', 'Điểm danh thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show attendance history for a class
     */
    public function history($classId)
    {
        $coach = Auth::user()->coach;

        $class = ClassModel::where('id', $classId)
            ->where('coach_id', $coach->id)
            ->with('club')
            ->firstOrFail();

        // Get attendance records for this class
        $attendanceRecords = Attendance::where('class_id', $classId)
            ->with('student', 'classModel')
            ->orderBy('date', 'desc')
            ->paginate(50);

        return view('coach.attendance.history', compact('class', 'attendanceRecords'));
    }
}
