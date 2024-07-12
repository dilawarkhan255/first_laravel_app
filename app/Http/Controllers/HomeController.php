<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobListing;

class HomeController extends Controller
{
    public function home()
    {
        $jobs = JobListing::where('status', 'enabled')
                          ->orderBy('created_at', 'desc')
                          ->take(6)->get();
        return view('home', compact('jobs'));
    }


    public function job_details($slug)
    {
        $job = JobListing::where('slug', $slug)->first();
        return view('job_details', ['job' => $job]);
    }

    public function view_job()
    {
        $jobs = JobListing::orderBy('created_at', 'desc')->take(6)->get();
        return view('view_job', compact('jobs'));
    }

    public function loadmorejobs(Request $request)
    {
        $start = $request->input('start');

        $jobs = JobListing::orderBy('created_at', 'desc')
            ->offset($start)
            ->limit(6)
            ->get();

        return response()->json([
            'jobs' => $jobs,
            'next' => $start + 6
        ]);
    }

}
