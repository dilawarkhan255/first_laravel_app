<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $subjects = [];

        for ($i = 0; $i < 50; $i++) { // Assuming you want to seed 50 subjects
            $subjects[] = [
                'name' => $faker->word,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('subjects')->insert($subjects);
    }
}

