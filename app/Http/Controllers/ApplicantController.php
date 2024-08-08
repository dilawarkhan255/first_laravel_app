<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
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
        $applicants = Applicant::with(['user', 'job']);

        return DataTables::of($applicants)
            ->addColumn('action', function($applicant) {
                $attachmentUrl = Storage::url($applicant->attachment);
                return '<a href="' . $attachmentUrl . '" target="_blank">View Resume</a>';
            })
            ->editColumn('created_at', function($applicant) {
                return $applicant->created_at->format('Y-m-d H:i:s');
            })
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

        return redirect()->back()->with('success', 'Application submitted successfully.');
    }



}
