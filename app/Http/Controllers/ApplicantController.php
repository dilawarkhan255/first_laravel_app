<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Attachment;
use App\Models\Job;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ApplicantController extends Controller
{
        public function index()
    {
        if (request()->ajax()) {
            $applicants = Applicant::with('jobListings', 'user')->select('applicants.*');

            return DataTables::of($applicants)
                ->addColumn('action', function($applicant) {
                    $attachment = Attachment::where('type', 'document')->where('attachable_id', $applicant->user_id)->first();
                    $link = $attachment ? $attachment->link : '#';
                    return '<a href="' . $link . '" target="_blank">View Resume</a>';
                })
                ->editColumn('created_at', function($applicant) {
                    return $applicant->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('user_name', function($applicant) {
                    return $applicant->user ? $applicant->user->name : 'N/A';
                })
                ->addColumn('user_email', function($applicant) {
                    return $applicant->user ? $applicant->user->email : 'N/A';
                })
                ->addColumn('joblisting_title', function($applicant) {
                    $jobTitles = $applicant->jobListings->pluck('title')->implode(', ');
                    return $jobTitles ?: 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('applicants.index');
    }

    public function store(Request $request, JobListing $job)
    {
        $request->validate([
            'file.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $files = $request->file('file');
            (new AttachmentController())->uploadMultiple($files, auth()->user()->id, 'User');
        }

        $applicant = Applicant::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
        ]);

        $applicant->jobListings()->attach($job->id);

        return redirect()->back()->with('success', 'Application submitted successfully.');
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();

        return redirect()->route('applicants.index')->with('success', 'Applicant deleted successfully.');
    }

}
