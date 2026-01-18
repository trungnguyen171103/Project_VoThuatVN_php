<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
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

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

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

        $user->delete();

        return back()->with('success', 'Xóa tài khoản thành công!');
    }
}
