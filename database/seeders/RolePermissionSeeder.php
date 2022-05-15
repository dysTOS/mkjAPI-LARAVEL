<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // AUTH permissions
        Permission::create(['name' => 'delete user']);

        // ROLE permissions
        Permission::create(['name' => 'read role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        // NOTEN permissions
        Permission::create(['name' => 'read noten']);
        Permission::create(['name' => 'edit noten']);
        Permission::create(['name' => 'delete noten']);
        Permission::create(['name' => 'assign noten']);

        // MITGLIEDER permissions
        Permission::create(['name' => 'read mitglieder']);
        Permission::create(['name' => 'edit mitglieder']);
        Permission::create(['name' => 'delete mitglieder']);
        Permission::create(['name' => 'assign mitglieder']);

        // AUSRUECKUNGEN permissions
        Permission::create(['name' => 'read ausrueckungen']);
        Permission::create(['name' => 'edit ausrueckungen']);
        Permission::create(['name' => 'delete ausrueckungen']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Mitglied']);
        $role1->givePermissionTo('read noten');
        $role1->givePermissionTo('read ausrueckungen');
        $role1->givePermissionTo('read mitglieder');


        Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider


    }
}
