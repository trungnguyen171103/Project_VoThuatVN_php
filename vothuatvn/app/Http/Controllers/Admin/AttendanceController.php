<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\ClassModel;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display attendance marking page
     */
    public function index(Request $request)
    {
        $classes = ClassModel::with('club')->where('status', 'active')->get();
        $students = collect();
        $attendances = [];
        $selectedClass = null;

        if ($request->class_id && $request->date) {
            $selectedClass = ClassModel::with('students')->findOrFail($request->class_id);
            $students = $selectedClass->students;

            // Get existing attendance records for this date
            $existingAttendances = Attendance::where('class_id', $request->class_id)
                ->where('date', $request->date)
                ->get()
                ->keyBy('student_id');

            foreach ($students as $student) {
                $attendances[$student->id] = $existingAttendances->get($student->id)?->status;
            }
        }

        return view('admin.attendances.index', compact('classes', 'students', 'attendances', 'selectedClass'));
    }

    /**
     * Mark attendance
     */
    public function mark(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
        ]);

        foreach ($request->attendances as $data) {
            Attendance::updateOrCreate(
                [
                    'class_id' => $request->class_id,
                    'student_id' => $data['student_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                ]
            );
        }

        return back()->with('success', 'Điểm danh thành công!');
    }

    /**
     * Get classes for selected club
     */
    public function getClubClasses($clubId)
    {
        $classes = ClassModel::where('club_id', $clubId)
            ->where('status', 'active')
            ->get();

        return response()->json($classes);
    }
}
