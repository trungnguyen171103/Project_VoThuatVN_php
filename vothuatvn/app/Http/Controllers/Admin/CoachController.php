<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoachController extends Controller
{
    /**
     * Display coach management page
     */
    public function index()
    {
        $coaches = Coach::with('user')->where('status', 'active')->paginate(15);
        return view('admin.coaches.index', compact('coaches'));
    }

    /**
     * Search user by username
     */
    public function search(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Không tìm thấy tài khoản với tên đăng nhập này.');
        }

        // Check if already a coach
        $isCoach = Coach::where('user_id', $user->id)->exists();

        return view('admin.coaches.search-result', compact('user', 'isCoach'));
    }

    /**
     * Assign coach role to user
     */
    public function assignCoach(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Create coach record
            Coach::create([
                'user_id' => $request->user_id,
                'status' => 'active',
            ]);

            // Update user role
            $user = User::find($request->user_id);
            $user->role = 'coach';
            $user->save();

            DB::commit();
            return redirect()->route('admin.coaches.index')->with('success', 'Phân quyền HLV thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove coach role
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $coach = Coach::findOrFail($id);
            $user = $coach->user;

            // Check if coach is assigned to any active classes
            if ($coach->classes()->where('status', 'active')->exists()) {
                return back()->with('error', 'Không thể xóa HLV đang được phân công dạy lớp.');
            }

            // Update user role back to student or user
            $user->role = 'user';
            $user->save();

            // Delete coach record
            $coach->delete();

            DB::commit();
            return back()->with('success', 'Đã xóa quyền HLV thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show form to assign coach
     */
    public function create()
    {
        return view('admin.coaches.create');
    }
}
