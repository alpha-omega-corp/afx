<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => config('seed.admin.email'),
            'password' => $this->password(),
        ]);
    }

    /**
     * The password to seed the administrator with.
     *
     * Anywhere but production the old literal stands in when nothing is
     * configured, so a fresh checkout seeds and signs in without setup. In
     * production a guessable administrator is a way into the back office, so
     * the seeder stops instead of creating one.
     */
    private function password(): string
    {
        $password = config('seed.admin.password');

        if (filled($password)) {
            return $password;
        }

        if (app()->isProduction()) {
            throw new RuntimeException(
                'Set SEED_ADMIN_PASSWORD before seeding: refusing to create an administrator with a default password in production.'
            );
        }

        return 'admin';
    }
}
