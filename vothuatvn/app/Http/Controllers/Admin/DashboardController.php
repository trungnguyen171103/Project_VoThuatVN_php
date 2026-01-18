<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Coach;
use App\Models\Club;
use App\Models\Attendance;
use App\Models\TuitionPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Warning Cards - Students with unpaid fees this month
        $studentsWithDebt = $this->getStudentsWithDebtThisMonth();

        // Warning Cards - Students absent >= 2 times per week
        $frequentlyAbsentStudents = $this->getFrequentlyAbsentStudents();

        // Warning Cards - Classes expiring soon (< 7 days)
        $expiringClasses = ClassModel::expiringSoon()->with('club', 'coach.user')->get();

        // Statistics Cards
        $totalStudents = Student::active()->count();
        $totalActiveClasses = ClassModel::active()->count();
        $totalCoaches = Coach::where('status', 'active')->count();
        $totalStudentsWithDebt = TuitionPayment::whereIn('status', ['pending', 'overdue'])
            ->distinct('student_id')
            ->count('student_id');

        // Chart: Student registrations by club this month
        $studentsByClub = $this->getStudentRegistrationsByClub();

        // Chart: Paid vs Unpaid tuition ratio
        $tuitionRatio = $this->getTuitionPaymentRatio();

        // Chart: New students by month (last 6 months)
        $newStudentsByMonth = $this->getNewStudentsByMonth();

        return view('admin.dashboard', compact(
            'studentsWithDebt',
            'frequentlyAbsentStudents',
            'expiringClasses',
            'totalStudents',
            'totalActiveClasses',
            'totalCoaches',
            'totalStudentsWithDebt',
            'studentsByClub',
            'tuitionRatio',
            'newStudentsByMonth'
        ));
    }

    private function getStudentsWithDebtThisMonth()
    {
        // Get tuition payments that are pending/overdue and due date has passed
        return TuitionPayment::whereIn('status', ['pending', 'overdue'])
            ->whereHas('tuition', function ($q) {
                $q->where('due_date', '<=', now()->toDateString());
            })
            ->with('student', 'tuition.classModel')
            ->get()
            ->unique('student_id'); // Only count each student once
    }

    private function getFrequentlyAbsentStudents()
    {
        // Get students absent >= 2 times in the last week
        $oneWeekAgo = now()->subWeek();

        return Attendance::where('status', 'absent')
            ->where('date', '>=', $oneWeekAgo)
            ->select('student_id', DB::raw('COUNT(*) as absent_count'))
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 2')
            ->with('student')
            ->get();
    }

    private function getStudentRegistrationsByClub()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $data = DB::table('students')
            ->join('class_students', 'students.id', '=', 'class_students.student_id')
            ->join('classes', 'class_students.class_id', '=', 'classes.id')
            ->join('clubs', 'classes.club_id', '=', 'clubs.id')
            ->whereBetween('students.registration_date', [$startOfMonth, $endOfMonth])
            ->select('clubs.name as club_name', DB::raw('COUNT(DISTINCT students.id) as student_count'))
            ->groupBy('clubs.name')
            ->get();

        return [
            'labels' => $data->pluck('club_name'),
            'values' => $data->pluck('student_count'),
        ];
    }

    private function getTuitionPaymentRatio()
    {
        $paid = TuitionPayment::where('status', 'paid')->count();
        $unpaid = TuitionPayment::whereIn('status', ['pending', 'overdue'])->count();

        return [
            'labels' => ['Đã đóng', 'Chưa đóng'],
            'values' => [$paid, $unpaid],
        ];
    }

    private function getNewStudentsByMonth()
    {
        $months = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $count = Student::whereBetween('registration_date', [$startOfMonth, $endOfMonth])->count();

            $months->push([
                'month' => $date->format('M Y'),
                'count' => $count,
            ]);
        }

        return [
            'labels' => $months->pluck('month'),
            'values' => $months->pluck('count'),
        ];
    }
}
