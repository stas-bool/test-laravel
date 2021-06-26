<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'read news']);
        Permission::create(['name' => 'edit news']);
        Permission::create(['name' => 'publish news']);
        Permission::create(['name' => 'change status']);

        Role::create(['name' => 'user'])
            ->givePermissionTo(['read news']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());
    }
}
