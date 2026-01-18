# Hệ thống Menu Admin & Coach - Tóm tắt

## Đã hoàn thành

### 1. Database Structure
- ✅ Migration: Thêm role vào users table (enum: admin, coach, student)
- ✅ Migration: Tạo bảng coaches
- ✅ Migration: Tạo bảng students
- ✅ Migration: Tạo bảng classes
- ✅ Migration: Tạo bảng class_students (pivot)
- ✅ Migration: Tạo bảng schedules
- ✅ Migration: Tạo bảng attendances
- ✅ Migration: Tạo bảng tuitions
- ✅ Migration: Tạo bảng tuition_payments

**Lưu ý:** Nếu cột role đã tồn tại, cần kiểm tra migration status và có thể cần rollback hoặc skip.

### 2. Models
- ✅ User model: Thêm role, methods isAdmin(), isCoach(), isStudent(), relationships
- ✅ Coach model: Với relationships
- ✅ Student model: Với relationships
- ✅ ClassModel model: Với relationships
- ✅ Schedule model
- ✅ Attendance model
- ✅ Tuition model
- ✅ TuitionPayment model

### 3. Middleware
- ✅ RoleMiddleware: Kiểm tra role
- ✅ AdminMiddleware: Chỉ admin
- ✅ CoachMiddleware: Chỉ coach

### 4. Layouts & UI
- ✅ `layouts/admin.blade.php`: Admin layout với sidebar
- ✅ `layouts/coach.blade.php`: Coach layout với sidebar
- ✅ `partials/sidebar-admin.blade.php`: Admin sidebar menu đầy đủ
- ✅ `partials/sidebar-coach.blade.php`: Coach sidebar menu

### 5. Controllers
- ✅ Admin/DashboardController
- ✅ Coach/DashboardController

### 6. Routes
- ✅ Admin routes: /admin/* với AdminMiddleware
- ✅ Coach routes: /coach/* với CoachMiddleware
- ✅ Placeholder routes cho tất cả các chức năng

### 7. Views
- ✅ admin/dashboard.blade.php
- ✅ coach/dashboard.blade.php

### 8. AuthController
- ✅ Cập nhật redirect theo role sau login

## Menu Items đã tạo

### Admin Menu:
- Dashboard
- Quản lý HLV
- Quản lý Học viên
- Quản lý Lớp học
- Lịch dạy
- Điểm danh
- Học phí (submenu: Tạo học phí, Danh sách nợ)
- Hồ sơ cá nhân
- Đăng xuất

### Coach Menu:
- Dashboard
- Danh sách học viên
- Lớp dạy (submenu: Danh sách lớp)
- Lịch dạy
- Điểm danh
- Hồ sơ cá nhân
- Đăng xuất

## Cần làm tiếp

### 1. Fix Migration
Nếu cột role đã tồn tại:
```bash
php artisan migrate:status
# Kiểm tra migration nào đã chạy
# Nếu cần, rollback và chạy lại
```

### 2. Đăng ký Middleware
Cần đăng ký middleware trong `bootstrap/app.php` (Laravel 11):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'coach' => \App\Http\Middleware\CoachMiddleware::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

### 3. Tạo Controllers đầy đủ
- CoachController (admin only)
- StudentController
- ClassController
- ScheduleController
- AttendanceController
- TuitionController (admin only)
- ProfileController

### 4. Tạo Views đầy đủ
- admin/coaches/index.blade.php
- admin/students/index.blade.php
- admin/classes/index.blade.php
- admin/schedules/index.blade.php
- admin/attendances/index.blade.php
- admin/tuitions/create.blade.php
- admin/tuitions/debts.blade.php
- admin/profile.blade.php
- coach/students/index.blade.php
- coach/classes/index.blade.php
- coach/schedules/index.blade.php
- coach/attendances/index.blade.php
- coach/profile.blade.php

### 5. Tạo Seeder (Optional)
Tạo sample data để test:
- Admin user
- Coach users
- Student users
- Classes, schedules, etc.

## Cách test

1. **Tạo user admin:**
```php
php artisan tinker
$user = \App\Models\User::create([
    'username' => 'admin',
    'name' => 'Admin',
    'email' => 'admin@vothuatvn.com',
    'phone' => '0123456789',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

2. **Tạo user coach:**
```php
$user = \App\Models\User::create([
    'username' => 'coach1',
    'name' => 'HLV 1',
    'email' => 'coach1@vothuatvn.com',
    'phone' => '0123456788',
    'password' => bcrypt('password'),
    'role' => 'coach'
]);
```

3. **Login và test:**
- Login với admin → redirect đến /admin/dashboard
- Login với coach → redirect đến /coach/dashboard

## Files đã tạo/cập nhật

### Migrations:
- 2026_01_08_080732_add_role_to_users_table.php
- 2026_01_08_080757_create_coaches_table.php
- 2026_01_08_080810_create_students_table.php
- 2026_01_08_080834_create_classes_table.php
- 2026_01_08_080844_create_class_students_table.php
- 2026_01_08_080853_create_schedules_table.php
- 2026_01_08_080903_create_attendances_table.php
- 2026_01_08_080938_create_tuitions_table.php
- 2026_01_08_081110_create_tuition_payments_table.php

### Models:
- User.php (updated)
- Coach.php
- Student.php
- ClassModel.php
- Schedule.php
- Attendance.php
- Tuition.php
- TuitionPayment.php

### Middleware:
- RoleMiddleware.php
- AdminMiddleware.php
- CoachMiddleware.php

### Controllers:
- Admin/DashboardController.php
- Coach/DashboardController.php

### Views:
- layouts/admin.blade.php
- layouts/coach.blade.php
- partials/sidebar-admin.blade.php
- partials/sidebar-coach.blade.php
- admin/dashboard.blade.php
- coach/dashboard.blade.php

### Routes:
- web.php (updated)

### Controllers:
- AuthController.php (updated - redirect theo role)


