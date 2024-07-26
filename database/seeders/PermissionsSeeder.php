<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Jobs permissions
            'jobs',
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
            'view-jobs',
            'bulk-delete-jobs',

            // Designations permissions
            'designations',
            'create-designations',
            'edit-designations',
            'delete-designations',
            'view-designations',

            // Students permissions
            'students',
            'create-students',
            'edit-students',
            'delete-students',
            'view-students',
            'bulk-delete-students',
            'assign-subjects-to-students',
            'unassign-subjects-from-students',

            // Subjects permissions
            'subjects',
            'create-subjects',
            'edit-subjects',
            'delete-subjects',
            'view-subjects',
            'bulk-delete-subjects',
            'assign-students-to-subjects',
            'unassign-students-from-subjects',

            // Roles permissions
            'roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-roles',

            //users permission
            'users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
