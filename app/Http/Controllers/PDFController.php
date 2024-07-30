<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF()
    {
        ini_set('memory_limit', '1G'); // Increase to 1G or as needed
        ini_set('max_execution_time', '600');

        $jobs = JobListing::where('status', 1)
        ->select('title', 'company', 'designation_id', 'location', 'status')
        ->orderBy('title', 'asc')
        ->get();

        $data = [
            'title' => 'Job Listings',
            'date' => date('m/d/Y'),
            'jobs' => $jobs
        ];

        $pdf = PDF::loadView('pdf.generatePDF', $data);

        return $pdf->download('job_listings.pdf');
    }
}
