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
        Permission::create(['name' => 'update noten']);
        Permission::create(['name' => 'delete noten']);
        Permission::create(['name' => 'assign noten']);

        // ROLE permissions
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'read role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'assign role']);

        // AUTH permissions
        Permission::create(['name' => 'delete user']);

        // create roles and assign existing permissions
        $mitglied = Role::create(['name' => 'Mitglied']);
        $mitglied->givePermissionTo('read noten');
        $mitglied->givePermissionTo('read ausrueckungen');
        $mitglied->givePermissionTo('read mitglieder');

        $notenarchiv = Role::create(['name' => 'Notenarchiv']);
        $notenarchiv->givePermissionTo('create noten');
        $notenarchiv->givePermissionTo('update noten');
        $notenarchiv->givePermissionTo('delete noten');

        $vorstand = Role::create(['name' => 'Mitgliederverwaltung']);
        $vorstand->givePermissionTo('create mitglieder');
        $vorstand->givePermissionTo('update mitglieder');
        $vorstand->givePermissionTo('delete mitglieder');

        $vorstand = Role::create(['name' => 'Terminverwaltung']);
        $vorstand->givePermissionTo('create ausrueckungen');
        $vorstand->givePermissionTo('update ausrueckungen');
        $vorstand->givePermissionTo('delete ausrueckungen');

        $vorstand = Role::create(['name' => 'Anwesenheits/Stück-Erfassung']);
        $vorstand->givePermissionTo('assign mitglieder');
        $vorstand->givePermissionTo('assign noten');

        $administrator = Role::create(['name' => 'Administration']);
        $administrator->givePermissionTo('create role');
        $administrator->givePermissionTo('read role');
        $administrator->givePermissionTo('update role');
        $administrator->givePermissionTo('delete role');
        $administrator->givePermissionTo('assign role');
        $administrator->givePermissionTo('delete user');


        Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider


    }
}
