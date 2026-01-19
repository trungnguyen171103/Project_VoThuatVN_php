<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập bằng username
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // Tìm user theo username
        $user = User::where('username', $credentials['username'])->first();

        // Kiểm tra user và password
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login thành công
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            // Log activity
            ActivityLogger::log('login', "Đăng nhập vào hệ thống", User::class, $user->id);

            // Redirect theo role
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Đăng nhập thành công!');
            } elseif ($user->isCoach()) {
                return redirect()->intended('/coach/dashboard')->with('success', 'Đăng nhập thành công!');
            }

            return redirect()->intended('/dashboard')->with('success', 'Đăng nhập thành công!');
        }

        // Login failed
        return back()->withErrors([
            'username' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->withInput($request->only('username'));
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản mới
     */
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9_]+$/',
                'unique:users,username'
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,11}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự',
            'username.max' => 'Tên đăng nhập không được quá 30 ký tự',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ thường, số và dấu gạch dưới',
            'username.unique' => 'Tên đăng nhập này đã được sử dụng',
            'name.required' => 'Vui lòng nhập họ tên',
            'name.max' => 'Họ tên không được quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được đăng ký',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại phải có 10 hoặc 11 chữ số',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        // Chuyển username về chữ thường để đảm bảo consistency
        $validated['username'] = strtolower($validated['username']);

        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Đăng ký thành công! Chào mừng đến với VoThuatVN.');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        // Log activity before logout
        ActivityLogger::log('logout', "Đăng xuất khỏi hệ thống");

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Đã đăng xuất thành công.');
    }
}

