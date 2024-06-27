<?php

namespace App\Http\Controllers;

use App\Models\JobDesignation;
use Illuminate\Http\Request;

class JobDesignationController extends Controller
{
    public function index()
    {
        $jobDesignations = JobDesignation::all();
        return view('designations.index', ['jobDesignations' => $jobDesignations]);
    }

    public function show(JobDesignation $jobDesignation)
    {
        return view('designations.show', ['designation' => $jobDesignation]);
    }
    
    public function create()
    {
        return view('designations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $jobDesignations = JobDesignation::create($request->all());

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }
    
    public function edit(JobDesignation $jobDesignation)
    {
        return view('designations.edit', ['jobDesignation' => $jobDesignation]);
    }

    public function update(Request $request, JobDesignation $jobDesignation)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
    
        $jobDesignation->update($request->all());
    
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }
    

    public function destroy(JobDesignation $jobDesignation)
    {
        $jobDesignation->delete();

        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
