<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class CoachScheduleController extends Controller
{
    /**
     * Display teaching schedule
     */
    public function index()
    {
        $coach = Auth::user()->coach;

        // Get schedules for classes taught by this coach
        $schedules = Schedule::whereHas('classModel', function ($q) use ($coach) {
            $q->where('coach_id', $coach->id);
        })
            ->with('classModel.club')
            ->where('date', '>=', now()->startOfMonth())
            ->where('date', '<=', now()->endOfMonth()->addMonth())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Group schedules by date for calendar view
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return $schedule->date->format('Y-m-d');
        });

        return view('coach.schedule.index', compact('schedules', 'schedulesByDate'));
    }
}
