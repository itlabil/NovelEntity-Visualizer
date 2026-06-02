<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission bawaan spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. BUAT LIST HAK AKSES (PERMISSIONS)
        Permission::create(['name' => 'manage all data']); // Akses penuh
        Permission::create(['name' => 'contribute data']); // Hanya bisa tambah data (butuh review)

        // 2. BUAT ROLE & PASANG HAK AKSESNYA
        $adminRole = Role::create(['name' => 'administrator']);
        $adminRole->givePermissionTo('manage all data');

        $contributorRole = Role::create(['name' => 'contributor']);
        $contributorRole->givePermissionTo('contribute data');

        // 3. BUAT AKUN UTAMA KAMUS (ADMINISTRATOR FULL AKSES)
        $admin = User::create([
            'name' => 'Ironist08 Admin',
            'email' => 'admin@sourcemedia.id',
            'password' => bcrypt('cuma1sampai9'),
        ]);
        $admin->assignRole($adminRole);

        // 4. BUAT AKUN BIASA (KONTRIBUTOR / AKUN BANTU)
        $helper = User::create([
            'name' => 'Helper',
            'email' => 'helper@sourcemedia.id',
            'password' => bcrypt('1sampai9'),
        ]);
        $helper->assignRole($contributorRole);
    }
}
