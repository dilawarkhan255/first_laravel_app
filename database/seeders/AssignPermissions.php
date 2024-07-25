<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class AssignPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();
        for ($i=0; $i < count($permissions) ; $i++) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permissions[$i]->id,
                'model_type' => 'App\Models\User',
                'model_id' => 1
            ]);
        }
    }
}
