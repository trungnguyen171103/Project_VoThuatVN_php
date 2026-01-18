<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Coach;
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

        $club->delete();
        return back()->with('success', 'Xóa câu lạc bộ thành công!');
    }
}
