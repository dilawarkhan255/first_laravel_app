<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobListing;


class JobsController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $jobs = JobListing::all();
        return view('index', ['jobs' => $jobs]);
    }

    public function show(JobListing $job)
    {
        return view('show', ['job' => $job]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'company' => 'required|max:255',
            'designation' => 'required|max:255',
            'description' => 'required',
            'location' => 'required|max:255',
        ]);

        $job = JobListing::create($request->all());

        return redirect()->route('index')->with('success', 'Job created successfully.');
    }

    public function edit(JobListing $job)
    {
        return view('edit', ['job' => $job]);
    }

    public function update(Request $request, JobListing $job)
    {
        $request->validate([
            'title' => 'required|max:255',
            'company' => 'required|max:255',
            'designation' => 'required|max:255',
            'description' => 'required',
            'location' => 'required|max:255',
        ]);

        $job->update($request->all());

        return redirect()->route('index')->with('success', 'Job updated successfully.');
    }

    public function destroy(JobListing $job)
    {
        $job->delete();

        return redirect()->route('index')->with('success', 'Job deleted successfully.');
    }
}