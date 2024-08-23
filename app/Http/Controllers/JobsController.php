<?php

namespace App\Http\Controllers;

use App\DataTables\Job_ListingDataTable;
use App\Exports\JobsExport;
use App\Imports\JobsImport;
use App\Jobs\JobCSVData;
use App\Models\JobDesignation;
use Illuminate\Http\Request;
use App\Models\JobListing;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redis;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class JobsController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
        $user = auth()->user();

        if ($user) {
            $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

            $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

            $allPermissions = array_unique(array_merge($userPermissions, $rolePermissions));

            if (in_array('jobs', $allPermissions) || array_intersect(['create-jobs', 'edit-jobs', 'delete-jobs'], $allPermissions)) {
                $this->middleware('permission:jobs|create-jobs|edit-jobs|delete-jobs', ['only' => ['index', 'show']]);
            }

            if (in_array('create-jobs', $allPermissions)) {
                $this->middleware('permission:create-jobs', ['only' => ['create', 'store']]);
            }

            if (in_array('edit-jobs', $allPermissions)) {
                $this->middleware('permission:edit-jobs', ['only' => ['edit', 'update']]);
            }

            if (in_array('view-jobs', $allPermissions)) {
                $this->middleware('permission:view-jobs', ['only' => ['show']]);
            }

            if (in_array('delete-jobs', $allPermissions)) {
                $this->middleware('permission:delete-jobs', ['only' => ['destroy']]);
            }

            // Optionally, deny access to all actions if no relevant permissions are present
            if (empty(array_intersect(['jobs', 'create-jobs', 'edit-jobs', 'delete-jobs', 'view-jobs'], $allPermissions))) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $next($request); // Proceed with the request
    });
}


    // public function index(Job_ListingDataTable $dataTable)
    // {
    //     $jobs = JobListing::with('designation')->get();
    //     return $dataTable->render('index', compact('jobs'));
    // }
    public function index(Request $request)
    {
        $companies = JobListing::pluck('company')->unique()->sort()->values();
        $locations = JobListing::pluck('location')->unique()->sort()->values();
        $designations = JobDesignation::pluck('name')->unique()->sort()->values();
        $titles = JobListing::pluck('title')->unique()->sort()->values();

        if ($request->ajax()) {

            $query = JobListing::with('designation');

            if ($request->has('title') && $request->title != '') {
                $query->where('title', $request->title);
            }

            if ($request->has('company') && $request->company != '') {
                $query->where('company', $request->company);
            }

            if ($request->has('designation') && $request->designation != '') {
                $query->whereHas('designation', function($q) use ($request) {
                    $q->where('name', $request->designation);
                });
            }

            if ($request->has('location') && $request->location != '') {
                $query->where('location', $request->location);
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('designation', function($row){
                    return $row->designation ? $row->designation->name : 'N/A';
                })
                ->addColumn('status_url', function($row){
                    return route('jobs.status', ['job' => $row->id]);
                })
                ->addColumn('show_url', function($row){
                    return route('jobs.show', ['job' => $row->id]);
                })
                ->addColumn('edit_url', function($row){
                    return route('jobs.edit', ['job' => $row->id]);
                })
                ->addColumn('delete_url', function($row){
                    return route('jobs.destroy', ['job' => $row->id]);
                })
                ->addColumn('action', function($row){
                    return '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('jobs.index', compact('companies', 'locations', 'designations', 'titles'));
    }


    public function show($id)
    {
        $job = Redis::get('job_listing_' . $id);

        if (empty($job)) {
            $job = JobListing::with('designation')->findOrFail($id);
            Redis::set('job_listing_' . $id, json_encode($job->toArray()));
        } else {
            $job = json_decode($job, true);
        }

        return view('jobs.show', ['job' => $job]);
    }




    public function create(JobListing $job)
    {
        $designations = JobDesignation::all();

        return view('jobs.create', ['job' => $job, 'designations' => $designations]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'company' => 'required|max:255',
            'description' => 'required',
            'location' => 'required|max:255',
            'designation_id' => 'required'
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['slug'] = 'dummy';
        $job = JobListing::create($validatedData);

        $job->slug = Str::slug($request->title . '_' . $job->id);
        $job->save();

        Redis::setex('job_listing_' . $job->id, 60, json_encode($job->toArray()));

        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }



    public function edit(JobListing $job)
    {
        $designations = JobDesignation::all();
        return view('jobs.edit', ['job' => $job, 'designations' => $designations]);
    }

    public function update(Request $request, JobListing $job)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'company' => 'required|max:255',
            'description' => 'required',
            'location' => 'required|max:255',
        ]);

        if(empty($job->slug)){
            $job->slug = Str::slug($request->title. '_' . $job->id);
            $job->save();
        }
        elseif($request->name != $job->name){

            $job->slug = Str::slug($request->title. '_' . $job->id);
            $job->save();
        }

        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(JobListing $job)
    {
        // Delete the cache entry
        Redis::del('job_listing_' . $job->id);

        // Check the Redis cache entry after deletion
        // $cachedJobAfter = Redis::get('job_listing_' . $job->id);
        // dd('After deletion:', $cachedJobAfter);

        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }


    public function status(JobListing $job)
    {
        $job->update(['status' => !$job->status]);
        return redirect()->back()->with('success', 'Job status updated successfully.');
    }

    public function export()
    {
        return Excel::download(new JobsExport, 'jobs.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        Excel::import(new JobsImport, $request->file('file'));

        return back()->with('success', 'Jobs imported successfully.');
    }
}
