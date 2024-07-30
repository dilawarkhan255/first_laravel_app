<?php

namespace App\Imports;

use App\Jobs\JobCSVData;
use App\Models\JobDesignation;
use App\Models\JobListing;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobsImport implements ToModel, WithChunkReading, WithHeadingRow, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $designation = JobDesignation::where('name',$row['designation'])->first();
        return new JobListing([
            'title' => $row['title'],
            'company' => $row['company'],
            'designation_id' => $designation->id,
            'description' => $row['description'],
            'location' => $row['location'],
            'slug' => $row['slug'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'status' => $row['status'] ?? true
        ]);
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 300;
    }

    /**
     * Handle the import process.
     */
    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunk(array $rows)
    {
        Bus::dispatch(new JobCSVData($rows, $this->headingRow()));
    }

    public function headingRow(): int
    {
        return 1;
    }
}
