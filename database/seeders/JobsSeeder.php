<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $jobs = [];

        // Retrieve all existing designation IDs from job_designations table
        $designationIds = DB::table('job_designations')->pluck('id')->toArray();

        for ($i = 0; $i < 1500; $i++) {
            $title = $faker->jobTitle;
            $jobs[] = [
                'title' => $title,
                'company' => $faker->company,
                'designation_id' => 1,
                'description' => $faker->text,
                'location' => $faker->city,
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1,
                'slug' => Str::slug($title . '-' . $i),
            ];
        }

        DB::table('job_listings')->insert($jobs);
    }
}
