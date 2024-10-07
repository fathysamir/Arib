<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permissions array
        $permissions = [
            'show users',
            'create users',
            'edit users',
            'delete users',
            ///////////////////////
            'show departments',
            'create departments',
            'edit departments',
            'delete departments',
            ///////////////////////
            'show projects',
            'create projects',
            'edit projects',
            'delete projects',
            ///////////////////////
            'show tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            // Add more permissions as needed
        ];

        // Create each permission if it does not exist
        foreach ($permissions as $permission) {
            $existed_permission = Permission::where('name', $permission)->first();
            if (!$existed_permission) {
                Permission::create(['name' => $permission]);
            }
        }

        // Roles array
        $roles = [
            'Admin',
            'Manager',
            'Employee',
        ];

        // Create each role if it does not exist
        foreach ($roles as $role) {
            $existed_role = Role::where('name', $role)->first();
            if (!$existed_role) {
                Role::create(['name' => $role]);
            }
        }

        // Admin Role - sync all permissions
        $admin_role = Role::where('name', 'Admin')->first();
        $all_permissions = Permission::pluck('id')->all();
        $admin_role->syncPermissions($all_permissions);

        // Manager Role - sync specific permissions
        $manager_role = Role::where('name', 'Manager')->first();
        $manager_permissions = Permission::whereIn('name', [
            'show tasks',
            'create tasks',
            'edit tasks',
            'delete tasks'
        ])->pluck('id')->all();
        $manager_role->syncPermissions($manager_permissions);

        // Employee Role - sync specific permissions
        $employee_role = Role::where('name', 'Employee')->first();
        $employee_permissions = Permission::whereIn('name', [
            'show tasks',
            'edit tasks',
        ])->pluck('id')->all();
        $employee_role->syncPermissions($employee_permissions);
        $user1 = User::create([
            'first_name' => 'Admin1',
            'last_name' => 'Admin1',
            'email' => 'admin@gmail.com',
            
            'phone' => null,
            'theme' =>'theme1',
            'password' => Hash::make('GMadmin159!48@26#1'),
        ]);
        

        

        $user1->assignRole([$admin_role->id]);
        

        
    }
}