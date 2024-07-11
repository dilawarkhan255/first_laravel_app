<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobListing;

class HomeController extends Controller
{
    public function home()
    {
        $jobs = JobListing::where('status', 'enabled')->get();
        return view('home', compact('jobs'));
    }

    public function job_details($slug)
    {
        $job = JobListing::where('slug', $slug)->first();
        return view('job_details', ['job' => $job]);
    }

}
