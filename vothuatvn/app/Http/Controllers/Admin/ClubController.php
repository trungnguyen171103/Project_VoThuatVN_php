<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Coach;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display all clubs
     */
    public function index()
    {
        $clubs = Club::with('coaches.user')->paginate(15);
        return view('admin.clubs.index', compact('clubs'));
    }

    /**
     * Show form to create new club
     */
    public function create()
    {
        $coaches = Coach::with('user')->where('status', 'active')->get();
        return view('admin.clubs.create', compact('coaches'));
    }

    /**
     * Store new club
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coaches' => 'nullable|array',
            'coaches.*' => 'exists:coaches,id',
        ]);

        $club = Club::create([
            'name' => $request->name,
            'status' => 'active',
        ]);

        // Attach coaches
        if ($request->coaches) {
            $club->coaches()->attach($request->coaches);
        }

        // Log activity
        ActivityLogger::log('created', "Tạo câu lạc bộ {$club->name}", Club::class, $club->id);

        return redirect()->route('admin.clubs.index')->with('success', 'Tạo câu lạc bộ thành công!');
    }

    /**
     * Show form to edit club
     */
    public function edit($id)
    {
        $club = Club::with('coaches')->findOrFail($id);
        $coaches = Coach::with('user')->where('status', 'active')->get();
        return view('admin.clubs.edit', compact('club', 'coaches'));
    }

    /**
     * Update club
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coaches' => 'nullable|array',
            'coaches.*' => 'exists:coaches,id',
        ]);

        $club = Club::findOrFail($id);

        $club->update([
            'name' => $request->name,
        ]);

        // Sync coaches
        $club->coaches()->sync($request->coaches ?? []);

        // Log activity
        ActivityLogger::log('updated', "Cập nhật câu lạc bộ {$club->name}", Club::class, $club->id);

        return redirect()->route('admin.clubs.index')->with('success', 'Cập nhật câu lạc bộ thành công!');
    }

    /**
     * Delete club
     */
    public function destroy($id)
    {
        $club = Club::findOrFail($id);

        // Check if club has active classes
        if ($club->classes()->where('status', 'active')->exists()) {
            return back()->with('error', 'Không thể xóa câu lạc bộ còn lớp học đang hoạt động.');
        }

        $clubName = $club->name;
        $club->delete();

        // Log activity
        ActivityLogger::log('deleted', "Xóa câu lạc bộ {$clubName}", Club::class, $id);

        return back()->with('success', 'Xóa câu lạc bộ thành công!');
    }
}
