<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;

class CoachClassController extends Controller
{
    /**
     * Display list of classes taught by coach
     */
    public function index()
    {
        $coach = Auth::user()->coach;

        $classes = ClassModel::where('coach_id', $coach->id)
            ->with('club', 'students')
            ->withCount('students')
            ->orderBy('status', 'desc')
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('coach.classes.index', compact('classes'));
    }

    /**
     * Show class details with student list
     */
    public function show($id)
    {
        $coach = Auth::user()->coach;

        $class = ClassModel::where('id', $id)
            ->where('coach_id', $coach->id)
            ->with('club', 'students')
            ->firstOrFail();

        return view('coach.classes.show', compact('class'));
    }
}
