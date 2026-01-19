<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by description
        if ($request->search) {
            $query->where('description', 'LIKE', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20);
        $users = User::orderBy('name')->get();

        // Get unique actions for filter
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity_logs.index', compact('logs', 'users', 'actions'));
    }
}
