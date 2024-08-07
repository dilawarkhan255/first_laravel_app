<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::with(['user', 'job'])->get();
        return view('applicants.index', compact('applicants'));
    }

    public function store(Request $request, JobListing $job)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string',
        ]);

        $existingApplication = Applicant::where('user_id', Auth::id())
        ->where('job_id', $job->id)
        ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        $resumePath = $request->file('resume')->store('resumes', 'public');

        Applicant::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'resume' => $resumePath,
            'cover_letter' => $request->cover_letter,
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully.');
    }

}
