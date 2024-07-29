<?php

namespace App\Exports;

use App\Models\JobListing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return JobListing::all();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Company',
            'Designation',
            'Description',
            'Location',
            'Slug',
            'Created At',
            'Updated At',
            'Status',
        ];
    }

    public function map($jobListing): array
    {
        return [
            $jobListing->title,
            $jobListing->company,
            $jobListing->designation ? $jobListing->designation->name : 'N/A',
            $jobListing->description,
            $jobListing->location,
            $jobListing->slug,
            $jobListing->created_at,
            $jobListing->updated_at,
            $jobListing->status,
        ];
    }
}
