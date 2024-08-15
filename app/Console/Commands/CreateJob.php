<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobListing;
use Illuminate\Support\Str;

class CreateJob extends Command
{
    protected $signature = 'createjob'; 
    protected $description = 'Create job entries or perform job-related tasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        JobListing::create([
            'title' => 'Sample Job Title',
            'company' => 'Sample Company',
            'designation_id' => 1,
            'description' => 'Sample job description.',
            'location' => 'Sample Location',
            'status' => '1',
            'slug' => Str::slug('Sample Job Title'),
        ]);

        $this->info('Job listing created successfully.');
    }
}
