<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
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

        // AUSRUECKUNGEN permissions
        Permission::create(['name' => 'create ausrueckungen']);
        Permission::create(['name' => 'read ausrueckungen']);
        Permission::create(['name' => 'update ausrueckungen']);
        Permission::create(['name' => 'delete ausrueckungen']);

        // MITGLIEDER permissions
        Permission::create(['name' => 'create mitglieder']);
        Permission::create(['name' => 'read mitglieder']);
        Permission::create(['name' => 'update mitglieder']);
        Permission::create(['name' => 'delete mitglieder']);
        Permission::create(['name' => 'assign mitglieder']);

        // NOTEN permissions
        Permission::create(['name' => 'create noten']);
        Permission::create(['name' => 'read noten']);
        Permission::create(['name' => 'edit noten']);
        Permission::create(['name' => 'delete noten']);
        Permission::create(['name' => 'assign noten']);

        // ROLE permissions
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'read role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        // AUTH permissions
        Permission::create(['name' => 'delete user']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Mitglied']);
        $role1->givePermissionTo('read noten');
        $role1->givePermissionTo('read ausrueckungen');
        $role1->givePermissionTo('read mitglieder');


        Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider


    }
}
