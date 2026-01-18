<?php

use Illuminate\Support\Facades\DB;

// Kiểm tra cấu trúc bảng users
echo "=== Checking users table structure ===\n";
$columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'users';");

foreach ($columns as $col) {
    echo "- {$col->column_name} ({$col->data_type})\n";
}

echo "\n=== All Users ===\n";
$users = DB::table('users')->select('id', 'username', 'email', 'role', 'account_status')->get();

foreach ($users as $user) {
    echo "ID: {$user->id} | Username: {$user->username} | Role: {$user->role} | Status: " . ($user->account_status ?? 'N/A') . "\n";
}

// Hỏi xem có muốn tạo admin không
echo "\n=== Do you want to create/update an admin account? ===\n";
echo "Enter username (or press Enter to skip): ";
$username = trim(fgets(STDIN));

if (!empty($username)) {
    $user = DB::table('users')->where('username', $username)->first();

    if ($user) {
        DB::table('users')->where('username', $username)->update([
            'role' => 'admin',
            'account_status' => 'active'
        ]);
        echo "✅ Updated '{$username}' to admin role!\n";
    } else {
        echo "❌ User '{$username}' not found!\n";
    }
}
