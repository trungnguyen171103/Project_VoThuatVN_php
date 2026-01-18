<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    /**
     * Display all user accounts
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'ILIKE', '%' . $request->search . '%')
                    ->orWhere('name', 'ILIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'ILIKE', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->account_status) {
            $query->where('account_status', $request->account_status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle account status (active/disabled)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent disabling own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể vô hiệu hóa tài khoản của chính mình!');
        }

        $newStatus = $user->account_status === 'active' ? 'disabled' : 'active';
        $user->update(['account_status' => $newStatus]);

        $message = $newStatus === 'disabled' ? 'Đã vô hiệu hóa tài khoản!' : 'Đã kích hoạt tài khoản!';

        return back()->with('success', $message);
    }

    /**
     * Delete account
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính mình!');
        }

        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản admin!');
        }

        $user->delete();

        return back()->with('success', 'Xóa tài khoản thành công!');
    }
}
