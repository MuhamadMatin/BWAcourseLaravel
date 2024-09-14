<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $teacherRole = Role::create([
            'name' => 'teacher'
        ]);

        $studentRole = Role::create([
            'name' => 'student'
        ]);

        $users = [
            [
                'name' => 'owner',
                'email' => 'owner@gmail.com',
                'password' => bcrypt('owner'),
                'occupation' => 'owner',
                'avatar' => 'images/default.png',
                'roles' => $ownerRole,
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
                'occupation' => 'admin',
                'avatar' => 'images/default.png',
                'roles' => $adminRole,
            ],
            [
                'name' => 'teacher',
                'email' => 'teacher@gmail.com',
                'password' => bcrypt('teacher'),
                'occupation' => 'teacher',
                'avatar' => 'images/default.png',
                'roles' => $teacherRole,
            ],
            [
                'name' => 'student',
                'email' => 'student@gmail.com',
                'password' => bcrypt('student'),
                'occupation' => 'student',
                'avatar' => 'images/default.png',
                'roles' => $studentRole,
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'occupation' => $user['occupation'],
                'avatar' => $user['avatar'],
            ])->assignRole($user['roles']);
        }
    }
}
