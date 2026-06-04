<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncAdminUser extends Command
{
    protected $signature = 'admin:sync';

    protected $description = 'Create or update the admin user from ADMIN_* environment variables';

    public function handle(): int
    {
        $email = config('admin.email');
        $password = config('admin.password');
        $name = config('admin.name', 'Admin');

        if (empty($email) || empty($password)) {
            $this->warn('Skipped: set ADMIN_EMAIL and ADMIN_PASSWORD in .env to bootstrap admin.');

            return self::SUCCESS;
        }

        if (strlen($password) < 8) {
            $this->error('ADMIN_PASSWORD must be at least 8 characters.');

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->info("Admin user synced for {$email}");

        return self::SUCCESS;
    }
}
