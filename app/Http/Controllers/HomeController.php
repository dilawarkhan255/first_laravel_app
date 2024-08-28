<?php

namespace App\Http\Controllers;

use App\Events\SendMailEvent;
use Illuminate\Http\Request;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


class HomeController extends Controller
{
    // public function home()
    // {
    //     $query = JobListing::where('status', '1');

    //     if (Auth::check() && Auth::user()->hasRole('Admin')) {
    //         // Admins see all jobs
    //     } else {
    //         // Non-admins see only their own
    //         $query->where('user_id', Auth::id());
    //     }

    //     $jobs = $query->orderBy('created_at', 'desc')->take(6)->get();
    //     return view('home/home', compact('jobs'));
    // }

        public function home()
    {
        $query = JobListing::where('status', '1');

        // Show all jobs without restricting by user ID or role
        $jobs = $query->orderBy('created_at', 'desc')->take(6)->get();

        return view('home/home', compact('jobs'));
    }


    public function job_details($slug)
    {
        $job = JobListing::where('slug', $slug)->first();

        if (!$job) {
            abort(404, 'Job not found.');
        }

        // If a user is logged in, check if they are allowed to view this job's details
        if (Auth::check() && !Auth::user()->hasRole('Admin') && $job->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this job.');
        }

        // Initialize favouriteJobs as an empty collection
        $favouriteJobs = collect();

        // Only fetch favourite jobs if the user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $favouriteJobs = $user->favouriteJobs()
                ->wherePivot('favourite', true)
                ->pluck('job_listings.id');
        }

        return view('home.job_details', [
            'job' => $job,
            'favouriteJobs' => $favouriteJobs
        ]);
    }


    public function view_job(Request $request)
    {
        $search = $request->input('search');
        View::share('search_job', $search);

        $query = JobListing::with('designation')->where('status', '1');

        if (Auth::check()) {
            if (!Auth::user()->hasRole('Admin')) {
                $query->where('user_id', Auth::id());
            }
        } else {
            // Optionally, handle unauthenticated users here, like showing no results or a message
            $query->where('user_id', null); // This will return no jobs
        }

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

        return view('home/view_job', compact('jobs', 'totalJobs'));
    }


    public function loadmorejobs(Request $request)
    {
        $start = $request->input('start');
        $search = $request->input('search');

        $query = JobListing::with('designation')->where('status', '1');

        if (!Auth::user()->hasRole('Admin')) {
            $query->where('user_id', Auth::id());
        }

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

    public function sendEmailToUsers(Request $request)
    {
        // Validate the request
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');

        // Get all users or specific users based on your logic
        $users = User::all(); // Or you could use a different query if needed

        foreach ($users as $user) {
            // Dispatch the event to send an email
            event(new SendMailEvent($user, $subject, $message));
        }

        return redirect()->back()->with('status', 'Emails sent successfully!');
    }
}
