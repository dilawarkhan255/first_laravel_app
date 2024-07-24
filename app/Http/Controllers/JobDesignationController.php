<?php

namespace App\Http\Controllers;

use App\Models\JobDesignation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class JobDesignationController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:designations-list|designations-create|designations-edit|designations-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:designations-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:designations-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:designations-show'], ['only' => ['show']]);
        $this->middleware(['permission:designations-delete'], ['only' => ['destroy']]);
    }

    // public function index()
    // {
    //     $jobDesignations = JobDesignation::all();
    //     return view('designations.index', ['jobDesignations' => $jobDesignations]);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jobDesignation = JobDesignation::select('*');

            return DataTables::of($jobDesignation)
                ->addColumn('show_url', function($row){
                    return route('designations.show', ['jobDesignation' => $row->id]);
                })
                ->addColumn('edit_url', function($row){
                    return route('designations.edit', ['jobDesignation' => $row->id]);
                })
                ->addColumn('delete_url', function($row){
                    return route('designations.destroy', ['jobDesignation' => $row->id]);
                })
                ->addColumn('action', function($row){
                    return '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('designations.index');
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
