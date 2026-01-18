<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// Home - redirect based on user role
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'coach') {
            return redirect('/coach/dashboard');
        }

        return redirect('/dashboard');
    }
    return redirect('/login');
});


// Dashboard (protected) - redirect based on role
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Redirect admin and coach to their dashboards
    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'coach') {
        return redirect('/coach/dashboard');
    }

    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Coach Management
    Route::get('/coaches', [\App\Http\Controllers\Admin\CoachController::class, 'index'])->name('coaches.index');
    Route::get('/coaches/create', [\App\Http\Controllers\Admin\CoachController::class, 'create'])->name('coaches.create');
    Route::post('/coaches/search', [\App\Http\Controllers\Admin\CoachController::class, 'search'])->name('coaches.search');
    Route::post('/coaches/assign', [\App\Http\Controllers\Admin\CoachController::class, 'assignCoach'])->name('coaches.assign');
    Route::delete('/coaches/{id}', [\App\Http\Controllers\Admin\CoachController::class, 'destroy'])->name('coaches.destroy');

    // Club Management
    Route::resource('clubs', \App\Http\Controllers\Admin\ClubController::class);

    // Class Management
    Route::get('/classes', [\App\Http\Controllers\Admin\ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [\App\Http\Controllers\Admin\ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [\App\Http\Controllers\Admin\ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{id}/edit', [\App\Http\Controllers\Admin\ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{id}', [\App\Http\Controllers\Admin\ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [\App\Http\Controllers\Admin\ClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/classes/{id}/students', [\App\Http\Controllers\Admin\ClassController::class, 'getStudents'])->name('classes.students');
    Route::get('/classes/{id}/available-students', [\App\Http\Controllers\Admin\ClassController::class, 'showAvailableStudents'])->name('classes.available-students');
    Route::post('/classes/{id}/add-student', [\App\Http\Controllers\Admin\ClassController::class, 'addStudent'])->name('classes.add-student');
    Route::delete('/classes/{classId}/remove-student/{studentId}', [\App\Http\Controllers\Admin\ClassController::class, 'removeStudent'])->name('classes.remove-student');
    Route::get('/clubs/{id}/coaches', [\App\Http\Controllers\Admin\ClassController::class, 'getClubCoaches'])->name('clubs.coaches');

    // Student Management
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // Schedule Management
    Route::get('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [\App\Http\Controllers\Admin\ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{id}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/schedules/clubs/{id}/classes', [\App\Http\Controllers\Admin\ScheduleController::class, 'getClubClasses'])->name('schedules.club-classes');
    Route::post('/schedules/check-conflict', [\App\Http\Controllers\Admin\ScheduleController::class, 'checkConflict'])->name('schedules.check-conflict');
    Route::get('/schedules/class-week', [\App\Http\Controllers\Admin\ScheduleController::class, 'getClassWeekSchedules'])->name('schedules.class-week');
    Route::delete('/schedules/class/{classId}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroyClassSchedules'])->name('schedules.destroy-class');

    // Attendance Management
    Route::get('/attendances', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/mark', [\App\Http\Controllers\Admin\AttendanceController::class, 'mark'])->name('attendances.mark');
    Route::get('/attendances/clubs/{id}/classes', [\App\Http\Controllers\Admin\AttendanceController::class, 'getClubClasses'])->name('attendances.club-classes');

    // Tuition Management
    Route::get('/tuitions/create', [\App\Http\Controllers\Admin\TuitionController::class, 'create'])->name('tuitions.create');
    Route::post('/tuitions', [\App\Http\Controllers\Admin\TuitionController::class, 'store'])->name('tuitions.store');
    Route::get('/tuitions/debts', [\App\Http\Controllers\Admin\TuitionController::class, 'debtList'])->name('tuitions.debts');
    Route::post('/tuitions/payments/{id}/mark-paid', [\App\Http\Controllers\Admin\TuitionController::class, 'markPaid'])->name('tuitions.mark-paid');
    Route::get('/tuitions/payments/{id}/print', [\App\Http\Controllers\Admin\TuitionController::class, 'printBill'])->name('tuitions.print-bill');
    Route::get('/tuitions/clubs/{id}/classes', [\App\Http\Controllers\Admin\TuitionController::class, 'getClubClasses'])->name('tuitions.club-classes');
    Route::get('/tuitions/classes/{id}/details', [\App\Http\Controllers\Admin\TuitionController::class, 'getClassDetails'])->name('tuitions.class-details');
    Route::put('/tuitions/class/{classId}', [\App\Http\Controllers\Admin\TuitionController::class, 'updateByClass'])->name('tuitions.update-by-class');
    Route::delete('/tuitions/class/{classId}', [\App\Http\Controllers\Admin\TuitionController::class, 'destroyByClass'])->name('tuitions.destroy-by-class');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [\App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change-password');

    // User Account Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::put('/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

// Coach Routes
Route::middleware(['auth', \App\Http\Middleware\CoachMiddleware::class])->prefix('coach')->name('coach.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Coach\CoachDashboardController::class, 'index'])->name('dashboard');

    // Classes (view only)
    Route::get('/classes', [\App\Http\Controllers\Coach\CoachClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/{id}', [\App\Http\Controllers\Coach\CoachClassController::class, 'show'])->name('classes.show');

    // Attendance
    Route::get('/attendance', [\App\Http\Controllers\Coach\CoachAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{scheduleId}', [\App\Http\Controllers\Coach\CoachAttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{scheduleId}', [\App\Http\Controllers\Coach\CoachAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/history/{classId}', [\App\Http\Controllers\Coach\CoachAttendanceController::class, 'history'])->name('attendance.history');

    // Students (view only)
    Route::get('/students', [\App\Http\Controllers\Coach\CoachStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [\App\Http\Controllers\Coach\CoachStudentController::class, 'show'])->name('students.show');

    // Schedule (view only)
    Route::get('/schedule', [\App\Http\Controllers\Coach\CoachScheduleController::class, 'index'])->name('schedule.index');

    // Debts (view only)
    Route::get('/debts', [\App\Http\Controllers\Coach\CoachDebtController::class, 'index'])->name('debts.index');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Coach\CoachProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\Coach\CoachProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Coach\CoachProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [\App\Http\Controllers\Coach\CoachProfileController::class, 'changePassword'])->name('profile.change-password');
});
