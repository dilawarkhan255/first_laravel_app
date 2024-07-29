<?php

namespace App\Imports;

use App\Models\JobListing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobsImport implements ToModel, WithChunkReading, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new JobListing([
            'title' => $row['title'], // 'title'
            'company' => $row['company'], // 'company'
            'designation_id' => $row['designations'], // 'designation_id'
            'description' => $row['description'], // 'description'
            'location' => $row['location'], // 'location'
            'slug' => $row['slug'], // 'slug'
            'created_at' => $row['created_at'], // 'created_at'
            'updated_at' => $row['updated_at'], // 'updated_at'
            'status' => $row['status'], // 'status'
        ]);
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }
}

