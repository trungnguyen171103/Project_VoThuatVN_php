<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachStudentController extends Controller
{
    /**
     * Display list of students in coach's classes
     */
    public function index()
    {
        $coach = Auth::user()->coach;

        // Get all students in classes taught by this coach
        $students = Student::whereHas('classes', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with([
                'classes' => function ($q) use ($coach) {
                    $q->where('coach_id', $coach->id);
                }
            ])
            ->orderBy('full_name')
            ->paginate(20);

        return view('coach.students.index', compact('students'));
    }

    /**
     * Show student profile and attendance history
     */
    public function show($id)
    {
        $coach = Auth::user()->coach;

        // Get student and verify they're in one of coach's classes
        $student = Student::whereHas('classes', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with([
                'classes' => function ($q) use ($coach) {
                    $q->where('coach_id', $coach->id)->with('club');
                }
            ])
            ->findOrFail($id);

        // Get attendance history for this student in coach's classes
        $attendanceHistory = Attendance::where('student_id', $id)
            ->whereHas('classModel', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->with('classModel')
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Calculate attendance statistics
        $totalSessions = Attendance::where('student_id', $id)
            ->whereHas('classModel', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->count();

        $presentCount = Attendance::where('student_id', $id)
            ->where('status', 'present')
            ->whereHas('classModel', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->count();

        $attendanceRate = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100, 1) : 0;

        return view('coach.students.show', compact('student', 'attendanceHistory', 'attendanceRate', 'totalSessions', 'presentCount'));
    }
}
