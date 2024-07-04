<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subject = Subject::select('*');

            return DataTables::of($subject)
                ->addIndexColumn()
                ->addColumn('show_url', function($row){
                    return route('subjects.show', ['subject' => $row->id]);
                })
                ->addColumn('edit_url', function($row){
                    return route('subjects.edit', ['subject' => $row->id]);
                })
                ->addColumn('delete_url', function($row){
                    return route('subjects.destroy', ['subject' => $row->id]);
                })
                ->addColumn('action', function($row){
                    return '<a href="' . $row->show_url . '" title="View"><i class="fas fa-eye" style="color: #000000;"></i></a> ' .
                        '<a href="' . $row->edit_url . '" title="Edit"><i class="fas fa-edit" style="color: #000000; margin-left:3px;"></i></a> ' .
                        '<form action="' . $row->delete_url . '" method="POST" style="display: inline;">' .
                        csrf_field() .
                        method_field("DELETE") .
                        '<i class="fas fa-trash show_confirm" style="cursor: pointer;"></i>' .
                        '</form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $students = Student::all();
        return view('subjects.index');
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show($id)
    {
        $subject = Subject::with('students')->find($id);
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
