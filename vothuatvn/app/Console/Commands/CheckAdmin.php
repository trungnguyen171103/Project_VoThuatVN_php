<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckAdmin extends Command
{
    protected $signature = 'admin:check {username?}';
    protected $description = 'Check and set admin role for a user';

    public function handle()
    {
        $this->info('=== All Users ===');
        $users = User::all(['id', 'username', 'email', 'role', 'account_status']);

        foreach ($users as $user) {
            $this->line("ID: {$user->id} | Username: {$user->username} | Role: {$user->role} | Status: {$user->account_status}");
        }

        $username = $this->argument('username');

        if (!$username) {
            $username = $this->ask('Enter username to make admin (or leave blank to skip)');
        }

        if ($username) {
            $user = User::where('username', $username)->first();

            if ($user) {
                $user->role = 'admin';
                $user->account_status = 'active';
                $user->save();

                $this->info("✅ Updated '{$username}' to admin role!");
            } else {
                $this->error("❌ User '{$username}' not found!");
            }
        }
    }
}
