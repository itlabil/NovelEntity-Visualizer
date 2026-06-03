<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //permission for is admin
        Permission::create(['name' => 'admin', 'guard_name' => 'web']);

        //permission for roles
        Permission::create(['name' => 'roles.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'web']);

        //permission for permissions
        Permission::create(['name' => 'permissions.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'permissions.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'permissions.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'permissions.delete', 'guard_name' => 'web']);

        //permission for users
        Permission::create(['name' => 'users.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'users.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);

        //permission for novels
        Permission::create(['name' => 'novels.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'novels.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'novels.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'novels.delete', 'guard_name' => 'web']);

        //permission for entities
        Permission::create(['name' => 'entities.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'entities.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'entities.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'entities.delete', 'guard_name' => 'web']);

        //permission for entity-aliases
        Permission::create(['name' => 'entity-aliases.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-aliases.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-aliases.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-aliases.delete', 'guard_name' => 'web']);

        //permission for entity-translations
        Permission::create(['name' => 'entity-translations.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-translations.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-translations.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'entity-translations.delete', 'guard_name' => 'web']);
    }
}
