<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking users table columns ===\n";

$columns = DB::select("
    SELECT column_name, data_type, column_default 
    FROM information_schema.columns 
    WHERE table_name = 'users' 
    ORDER BY ordinal_position
");

foreach ($columns as $col) {
    echo "- {$col->column_name} ({$col->data_type})\n";
}

echo "\n=== Checking for specific columns ===\n";
echo "username: " . (Schema::hasColumn('users', 'username') ? '✓ EXISTS' : '✗ MISSING') . "\n";
echo "phone: " . (Schema::hasColumn('users', 'phone') ? '✓ EXISTS' : '✗ MISSING') . "\n";
echo "role: " . (Schema::hasColumn('users', 'role') ? '✓ EXISTS' : '✗ MISSING') . "\n";
echo "account_status: " . (Schema::hasColumn('users', 'account_status') ? '✓ EXISTS' : '✗ MISSING') . "\n";

echo "\n=== Sample user data ===\n";
$users = DB::table('users')->select('id', 'username', 'email', 'role')->limit(3)->get();
foreach ($users as $user) {
    echo "ID: {$user->id} | Username: " . ($user->username ?? 'N/A') . " | Email: {$user->email} | Role: " . ($user->role ?? 'N/A') . "\n";
}
