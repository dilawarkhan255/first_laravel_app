<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Jobs permissions
        Permission::create(['name' => 'create-jobs']);
        Permission::create(['name' => 'edit-jobs']);
        Permission::create(['name' => 'delete-jobs']);
        Permission::create(['name' => 'view-jobs']);
        Permission::create(['name' => 'bulk-delete-jobs']);

        // Designations permissions
        Permission::create(['name' => 'create-designations']);
        Permission::create(['name' => 'edit-designations']);
        Permission::create(['name' => 'delete-designations']);
        Permission::create(['name' => 'view-designations']);

        // Students permissions
        Permission::create(['name' => 'create-students']);
        Permission::create(['name' => 'edit-students']);
        Permission::create(['name' => 'delete-students']);
        Permission::create(['name' => 'view-students']);
        Permission::create(['name' => 'bulk-delete-students']);
        Permission::create(['name' => 'assign-subjects-to-students']);
        Permission::create(['name' => 'unassign-subjects-from-students']);

        // Subjects permissions
        Permission::create(['name' => 'create-subjects']);
        Permission::create(['name' => 'edit-subjects']);
        Permission::create(['name' => 'delete-subjects']);
        Permission::create(['name' => 'view-subjects']);
        Permission::create(['name' => 'bulk-delete-subjects']);
        Permission::create(['name' => 'assign-students-to-subjects']);
        Permission::create(['name' => 'unassign-students-from-subjects']);

        // Roles permissions
        Permission::create(['name' => 'create-roles']);
        Permission::create(['name' => 'edit-roles']);
        Permission::create(['name' => 'delete-roles']);
        Permission::create(['name' => 'view-roles']);
    }
}
