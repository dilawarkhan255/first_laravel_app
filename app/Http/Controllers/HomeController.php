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
        $jobs = JobListing::where('status', 'enabled')->orderBy('created_at', 'asc')->take(3)->get();
        $totalJobs = JobListing::where('status', 'enabled')->count();
        return view('view_job', compact('jobs', 'totalJobs'));
    }


    public function loadmorejobs(Request $request)
    {
        $start = $request->input('start');

        $jobs = JobListing::where('status', 'enabled')
            ->orderBy('created_at', 'asc')
            ->offset($start)
            ->limit(3)
            ->get();
        $all_loaded = false;
        $next = $start + 3;
        $totalJobs = JobListing::where('status', 'enabled')->count();
        if($next >= $totalJobs){
            $all_loaded = true;
        }
        return response()->json([
            'jobs' => $jobs,
            'next' => $next,
            'totalJobs' => $totalJobs,
            'all_loaded' => $all_loaded
        ]);
    }
}
