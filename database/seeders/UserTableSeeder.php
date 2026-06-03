<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =======================================
        // 1. CREATE ADMIN USER
        // =======================================
        $admin = User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@sourcemedia.id',
            'password'  => bcrypt('cuma1sampai9'),
        ]);

        // Get or create 'admin' role
        $adminRole = Role::where('name', 'admin')->first();

        // Assign all permissions to admin role
        $allPermissions = Permission::all();

        // Assign all permissions to admin role
        $adminRole->syncPermissions($allPermissions);

        // Assign admin role to admin user
        $admin->assignRole($adminRole);

        // =======================================
        // 2. CREATE HELPER USER
        // =======================================
        $helper = User::create([
            'name'      => 'Helper',
            'email'     => 'helper@sourcemedia.id',
            'password'  => bcrypt('1sampai9'),
        ]);

        // Get 'helper' role
        $helperRole = Role::where('name', 'contributor')->first();

        // Get permissions for helper role
        $helperPermissions = Permission::whereIn('name', [
            'novels.index',
            'novels.create',
            'novels.edit',
            'entities.index',
            'entities.create',
            'entities.edit',
            'entity-aliases.index',
            'entity-aliases.create',
            'entity-aliases.edit',
            'entity-translations.index',
            'entity-translations.create',
            'entity-translations.edit',
        ])->get();

        // Assign only specific permissions to helper role
        $helperRole->syncPermissions($helperPermissions);

        // Assign helper role to helper user
        $helper->assignRole($helperRole);
    }
}
