<?php

namespace App\Http\Controllers;

use App\DataTables\Job_ListingDataTable;
use App\Models\JobDesignation;
use Illuminate\Http\Request;
use App\Models\JobListing;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public function home()
    {
        return view('jobs.home');
    }

    // public function index(Job_ListingDataTable $dataTable)
    // {
    //     $jobs = JobListing::with('designation')->get();
    //     return $dataTable->render('index', compact('jobs'));
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jobs = JobListing::select('*');

            return DataTables::of($jobs)
                ->addIndexColumn()
                ->addColumn('designation', function($row){
                    $designation = JobDesignation::find($row->designation_id);
                    return $designation->name;
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

        return view('jobs.index');
    }

    public function show(JobListing $job)
    {
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

        $validatedData['slug'] = 'dummy';
        $job = JobListing::create($validatedData);

        $job->slug = Str::slug($request->title . '_' . $job->id);
        $job->save();

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
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function status(JobListing $job)
    {
        $job->update(['status' => !$job->status]);
        return redirect()->back()->with('success', 'Job status updated successfully.');
    }
}
