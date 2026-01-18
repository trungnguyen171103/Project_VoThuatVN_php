<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\TuitionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachDebtController extends Controller
{
    /**
     * Display students with unpaid tuition in coach's classes
     */
    public function index(Request $request)
    {
        $coach = Auth::user()->coach;

        // Get classes taught by this coach for filter
        $classes = \App\Models\ClassModel::where('coach_id', $coach->id)
            ->where('status', 'active')
            ->with('club')
            ->get();

        // Build query for debts
        $query = TuitionPayment::whereIn('status', ['pending', 'overdue'])
            ->whereHas('tuition.classModel', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->whereHas('tuition', function ($q) {
                $q->where('due_date', '<=', now()->toDateString());
            })
            ->with('student', 'tuition.classModel.club');

        // Filter by class if selected
        if ($request->class_id) {
            $query->whereHas('tuition', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $debts = $query->orderByRaw("CASE WHEN status = 'overdue' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('coach.debts.index', compact('debts', 'classes'));
    }
}
