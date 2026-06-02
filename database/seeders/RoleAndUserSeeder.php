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
        // 1. Reset cache permission bawaan spatie agar tidak menyangkut data lama
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. BUAT LIST HAK AKSES (PERMISSIONS) DENGAN MENENTUKAN GUARD SECARA TEGAS
        $manageAll = Permission::findOrCreate('manage all data', 'api');
        $contributeData = Permission::findOrCreate('contribute data', 'api');

        // 3. BUAT ROLE & PASANG HAK AKSESNYA
        $adminRole = Role::findOrCreate('administrator', 'api');
        // Langsung sinkronisasikan objek permission-nya, bukan string-nya saja
        $adminRole->givePermissionTo($manageAll);

        $contributorRole = Role::findOrCreate('contributor', 'api');
        $contributorRole->givePermissionTo($contributeData);

        // 4. BUAT AKUN UTAMA KAMUS (ADMINISTRATOR FULL AKSES)
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sourcemedia.id',
            'password' => bcrypt('cuma1sampai9'),
        ]);
        // Pastikan Spatie tahu kita pasang role untuk guard api
        $admin->assignRole($adminRole);

        // 5. BUAT AKUN BIASA (KONTRIBUTOR / AKUN BANTU)
        $helper = User::create([
            'name' => 'Helper',
            'email' => 'helper@sourcemedia.id',
            'password' => bcrypt('1sampai9'),
        ]);
        $helper->assignRole($contributorRole);
    }
}