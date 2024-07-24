<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Dilawar Khan',
            'email' => 'dk02713@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $superAdmin->assignRole('Super Admin');
    }
}
