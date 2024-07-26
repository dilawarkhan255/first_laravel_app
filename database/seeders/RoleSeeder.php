<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);

        $permissions = [
            'jobs',
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
            'view-jobs',
            'bulk-delete-jobs',

            'designations',
            'create-designations',
            'edit-designations',
            'delete-designations',
            'view-designations',

            'students',
            'create-students',
            'edit-students',
            'delete-students',
            'view-students',
            'bulk-delete-students',
            'assign-subjects-to-students',
            'unassign-subjects-from-students',

            'subjects',
            'create-subjects',
            'edit-subjects',
            'delete-subjects',
            'view-subjects',
            'bulk-delete-subjects',
            'assign-students-to-subjects',
            'unassign-students-from-subjects',

            'roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-roles',

            'users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
        ];

        // Assign all permissions to Admin
        $admin->givePermissionTo($permissions);

        // // Assign product-related permissions to Product Manager
        // $productManager->givePermissionTo([
        //     'create-product',
        //     'edit-product',
        //     'delete-product'
        // ]);
    }
}
