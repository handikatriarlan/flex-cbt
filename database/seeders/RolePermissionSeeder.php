<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }

        $teacherRole = Role::create([
            'name' => 'teacher',
        ]);

        $teacherRole->givePermissionTo([
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
        ]);

        $studentRole = Role::create([
            'name' => 'student',
        ]);

        $studentRole->givePermissionTo([
            'view courses',
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123123123'),
        ]);

        $user->assignRole($teacherRole);
    }
}
