<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Coach;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by username or phone
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Order by role priority: admin > coach > user, then by created_at
        $users = $query->orderByRaw("
            CASE 
                WHEN role = 'admin' THEN 1
                WHEN role = 'coach' THEN 2
                WHEN role = 'user' THEN 3
                ELSE 4
            END
        ")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent changing admin account status
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể thay đổi trạng thái tài khoản admin!');
        }

        $oldStatus = $user->status;
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        // Log activity
        $action = $user->status === 'active' ? 'Mở khóa' : 'Khóa';
        ActivityLogger::log('toggle_status', "{$action} tài khoản {$user->username}", User::class, $user->id);

        $message = $user->status === 'active' ? 'Đã mở khóa tài khoản!' : 'Đã khóa tài khoản!';
        return back()->with('success', $message);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản quản trị viên!');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính mình!');
        }

        $username = $user->username;
        $user->delete();

        // Log activity
        ActivityLogger::log('deleted', "Xóa tài khoản {$username}", User::class, $id);

        return back()->with('success', 'Xóa tài khoản thành công!');
    }

    /**
     * Update user role (unified method for role changes)
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,coach',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $newRole = $request->role;
            $oldRole = $user->role;

            // Prevent changing admin role
            if ($oldRole === 'admin') {
                return back()->with('error', 'Không thể thay đổi vai trò của tài khoản admin!');
            }

            // If no change, return
            if ($oldRole === $newRole) {
                return back()->with('info', 'Vai trò không thay đổi.');
            }

            // Handle role change
            if ($newRole === 'coach') {
                // Assign coach role
                Coach::create([
                    'user_id' => $user->id,
                    'status' => 'active',
                ]);
                $user->role = 'coach';
                $message = 'Phân quyền HLV thành công!';
            } else {
                // Remove coach role
                $coach = Coach::where('user_id', $user->id)->first();

                if ($coach) {
                    // Check if coach is assigned to any active classes
                    if ($coach->classes()->where('status', 'active')->exists()) {
                        DB::rollBack();
                        return back()->with('error', 'Không thể xóa quyền HLV đang được phân công dạy lớp!');
                    }
                    $coach->delete();
                }

                $user->role = 'user';
                $message = 'Đã xóa quyền HLV thành công!';
            }

            $user->save();

            // Log activity
            $action = $newRole === 'coach' ? 'assign_coach' : 'remove_coach';
            $description = $newRole === 'coach'
                ? "Phân quyền HLV cho {$user->username}"
                : "Xóa quyền HLV của {$user->username}";
            ActivityLogger::log($action, $description, User::class, $user->id);

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Assign coach role to user
     */
    public function assignCoach($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Check if already a coach
            if ($user->role === 'coach') {
                return back()->with('error', 'Tài khoản này đã là HLV!');
            }

            // Check if admin
            if ($user->role === 'admin') {
                return back()->with('error', 'Không thể phân quyền HLV cho tài khoản admin!');
            }

            // Create coach record
            Coach::create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            // Update user role
            $user->role = 'coach';
            $user->save();

            DB::commit();
            return back()->with('success', 'Phân quyền HLV thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove coach role from user
     */
    public function removeCoach($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Check if user is a coach
            if ($user->role !== 'coach') {
                return back()->with('error', 'Tài khoản này không phải HLV!');
            }

            $coach = Coach::where('user_id', $user->id)->first();

            if (!$coach) {
                return back()->with('error', 'Không tìm thấy thông tin HLV!');
            }

            // Check if coach is assigned to any active classes
            if ($coach->classes()->where('status', 'active')->exists()) {
                return back()->with('error', 'Không thể xóa quyền HLV đang được phân công dạy lớp!');
            }

            // Update user role back to user
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
}
