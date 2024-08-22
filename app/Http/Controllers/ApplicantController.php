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
    public function __construct()
{
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        if ($user) {
            // Get the user's direct permissions
            $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

            // Get the user's role permissions (if any)
            $userRoles = $user->roles;

            // Fetch permissions associated with the user's roles, if any
            if ($userRoles->isNotEmpty()) {
                $rolePermissions = $userRoles->map->permissions->flatten()->pluck('name')->unique()->toArray();
            } else {
                $rolePermissions = [];
            }

            // Combine both user and role permissions
            $allPermissions = array_merge($userPermissions, $rolePermissions);

            // Apply middleware for each permission based on combined permissions
            if (in_array('view-applicants', $allPermissions)) {
                $this->middleware('permission:view-applicants', ['only' => ['index', 'show']]);
            }

            if (in_array('applicants', $allPermissions)) {
                // Assuming 'applicants' includes permissions for create, edit, delete
                $this->middleware('permission:applicants', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
            } else {
                // Deny access to actions if no 'view-applicants' permission is present
                if (!in_array('view-applicants', $allPermissions) && !in_array('applicants', $allPermissions)) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        return $next($request);
    });
}


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
