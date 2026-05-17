<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaratrustRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh fitur aplikasi.',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Mengelola data operasional sekolah dan PPDB.',
            ],
            [
                'name' => 'siswa',
                'display_name' => 'Siswa',
                'description' => 'Akses pengguna siswa pada fitur yang diizinkan.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::query()->updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );
        }

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@schoolketapang.test',
                'password' => 'password',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin Sekolah',
                'email' => 'admin@schoolketapang.test',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Siswa Demo',
                'email' => 'siswa@schoolketapang.test',
                'password' => 'password',
                'role' => 'siswa',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::query()->updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
