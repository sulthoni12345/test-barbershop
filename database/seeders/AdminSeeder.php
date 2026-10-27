<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Membuat akun admin awal untuk deployment Docker.
 *
 * Nilai diambil dari environment (file .env):
 *   ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD
 *
 * Aman dijalankan berulang (idempoten): dipanggil oleh docker/entrypoint.sh
 * setiap container start, tanpa membuat akun ganda dan tanpa menimpa
 * password yang sudah diganti lewat aplikasi.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $name     = env('ADMIN_NAME', 'Admin Barbershop');
        $email    = env('ADMIN_EMAIL', 'admin@barbershop.test');
        $password = env('ADMIN_PASSWORD');

        $admin = User::where('email', $email)->first();

        if ($admin) {
            // Akun sudah ada: pastikan role tetap admin, password dibiarkan.
            if ($admin->role !== 'admin') {
                $admin->update(['role' => 'admin']);
            }

            $this->command?->info("Akun admin sudah ada: {$email}");

            return;
        }

        if (empty($password)) {
            throw new RuntimeException('ADMIN_PASSWORD belum diisi di file .env');
        }

        User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => $password, // di-hash otomatis oleh cast 'hashed' pada model User
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command?->info("Akun admin dibuat: {$email}");
    }
}
