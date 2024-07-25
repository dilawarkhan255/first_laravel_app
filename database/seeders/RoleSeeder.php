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
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
            'view-jobs',
            'bulk-delete-jobs',

            'create-designations',
            'edit-designations',
            'delete-designations',
            'view-designations',

            'create-students',
            'edit-students',
            'delete-students',
            'view-students',
            'bulk-delete-students',
            'assign-subjects-to-students',
            'unassign-subjects-from-students',

            'create-subjects',
            'edit-subjects',
            'delete-subjects',
            'view-subjects',
            'bulk-delete-subjects',
            'assign-students-to-subjects',
            'unassign-students-from-subjects',

            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-roles',
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
