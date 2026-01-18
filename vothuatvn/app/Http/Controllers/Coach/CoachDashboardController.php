<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\TuitionPayment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $coach = Auth::user()->coach;

        // Get classes currently teaching
        $classes = ClassModel::where('coach_id', $coach->id)
            ->where('status', 'active')
            ->withCount('students')
            ->with('club')
            ->get();

        // Get today's schedule
        $todaySchedules = Schedule::whereHas('classModel', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->whereDate('date', today())
            ->with('classModel.club')
            ->orderBy('start_time')
            ->get();

        // Get frequently absent students (in coach's classes)
        $frequentlyAbsentStudents = $this->getFrequentlyAbsentStudents($coach->id);

        // Statistics
        $totalStudents = ClassModel::where('coach_id', $coach->id)
            ->where('status', 'active')
            ->withCount('students')
            ->get()
            ->sum('students_count');

        $totalClasses = $classes->count();

        // Students with debt (in coach's classes)
        $studentsWithDebt = TuitionPayment::whereIn('status', ['pending', 'overdue'])
            ->whereHas('tuition.classModel', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->whereHas('tuition', function ($q) {
                $q->where('due_date', '<=', now()->toDateString());
            })
            ->with('student', 'tuition.classModel')
            ->get()
            ->unique('student_id');

        return view('coach.dashboard', compact(
            'classes',
            'todaySchedules',
            'frequentlyAbsentStudents',
            'totalStudents',
            'totalClasses',
            'studentsWithDebt'
        ));
    }

    private function getFrequentlyAbsentStudents($coachId)
    {
        $oneWeekAgo = now()->subWeek();

        return Attendance::where('status', 'absent')
            ->where('date', '>=', $oneWeekAgo)
            ->whereHas('classModel', function ($q) use ($coachId) {
                $q->where('coach_id', $coachId);
            })
            ->select('student_id', \DB::raw('COUNT(*) as absent_count'))
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 2')
            ->with('student')
            ->get();
    }
}
