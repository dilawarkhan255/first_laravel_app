<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobListing;
use Illuminate\Support\Facades\View;

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

    public function view_job(Request $request)
    {
        $search = $request->input('search');
        View::share('search_job', $search);

        $query = JobListing::with('designation')->where('status', 'enabled');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%")
                    ->orWhereHas('designation', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->take(3)->get();
        $totalJobs = $query->count();

        return view('view_job', compact('jobs', 'totalJobs'));
    }

    public function loadmorejobs(Request $request)
    {
        $start = $request->input('start');
        $search = $request->input('search');

        $query = JobListing::with('designation')->where('status', 'enabled');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%")
                    ->orWhereHas('designation', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        $totalJobs = $query->count();
        $jobs = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take(3)
            ->get();

        $all_loaded = $start + 3 >= $totalJobs;

        return response()->json([
            'jobs' => $jobs,
            'next' => $start + 3,
            'totalJobs' => $totalJobs,
            'all_loaded' => $all_loaded
        ]);
    }

    // public function search(Request $request)
    // {
    //     $search = $request->input('search');
    //     $results = JobListing::where('title', 'like', "%$search%")
    //                           ->where('status', 'enabled')
    //                           ->orderBy('created_at', 'desc')
    //                           ->get();
    //     return view('view_job', ['jobs' => $results, 'totalJobs' => $results->count()]);
    // }

}
