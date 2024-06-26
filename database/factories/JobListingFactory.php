<?php

namespace Database\Factories;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobListingFactory extends Factory
{
    protected $model = JobListing::class;

    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle,
            'company' => $this->faker->company,
            'designation' => $this->faker->jobTitle,
            'location' => $this->faker->city,
            'description' => $this->faker->paragraph,
        ];
    }
}
