<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{

// public function index()
// {
//     $students = Student::all();
//     return view('students.index', compact('students'));
// }

    public function index(Request $request)
        {
            if ($request->ajax()) {
                $students = Student::select('*');

                return DataTables::of($students)
                    ->addIndexColumn()
                    ->addColumn('show_url', function($row){
                        return route('students.show', ['student' => $row->id]);
                    })
                    ->addColumn('edit_url', function($row){
                        return route('students.edit', ['student' => $row->id]);
                    })
                    ->addColumn('delete_url', function($row){
                        return route('students.destroy', ['student' => $row->id]);
                    })
                    ->addColumn('action', function($row){
                        return '<a href="' . $row->show_url . '" title="View"><i class="fas fa-eye" style="color: #000000;"></i></a> ' .
                            '<a href="' . $row->edit_url . '" title="Edit"><i class="fas fa-edit" style="color: #000000; margin-left:3px;"></i></a> ' .
                            '<form action="' . $row->delete_url . '" method="POST" style="display: inline;">' .
                            csrf_field() .
                            method_field("DELETE") .
                            '<i class="fas fa-trash show_confirm" style="cursor: pointer;"></i>' .
                            '</form> ' .
                            '<a href="javascript:void(0)" onclick="openAssignModal(' . $row->id . ')" title="Assign Subjects"><i class="fas fa-book" style="color: #000000; margin-left:3px;"></i></a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $subjects = Subject::all();
            return view('students.index', compact('subjects'));
        }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    // public function show(Student $student)
    // {
    //     return view('students.show', compact('student'));
    // }

    public function show($id)
    {
        $student = Student::with('subjects')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required',
            'address' => 'required',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function assignSubjects(Request $request)
    {
        $student = Student::find($request->student_id);
        $student->subjects()->sync($request->subjects);

        Session::flash('success', 'Subjects assigned successfully.');

        return response()->json(['success' => true]);
    }

    public function unassignSubjects(Request $request)
    {
        $student = Student::find($request->student_id);
        $student->subjects()->detach($request->subjects);

        Session::flash('success', 'Subjects unassigned successfully.');

        return response()->json(['success' => true]);
    }

}
