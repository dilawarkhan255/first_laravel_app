<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class SubjectController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:list-subjects|create-subjects|edit-subjects|delete-subjects', ['only' => ['index','store']]);
         $this->middleware('permission:create-subjects', ['only' => ['create','store']]);
         $this->middleware('permission:edit-subjects', ['only' => ['edit','update']]);
         $this->middleware('permission:delete-subjects', ['only' => ['destroy']]);
    }

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
        $students = Student::select('id','name')->get()->toArray();
        return view('subjects.index', compact('students'));
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

    public function assignStudents(Request $request)
    {
        $subject = Subject::find($request->subject_id);
        $subject->students()->attach($request->students);
        Session::flash('success', 'Students assigned successfully.');

        return response()->json(['success' => true]);
    }


    public function unassignStudents(Request $request)
    {
        $subject = Subject::find($request->subject_id);
        $subject->students()->detach($request->student_id);
        return response()->json(['success' => true]);
    }

    public function getAvailableStudents($id)
    {
        $assignedStudents = DB::table('student_subject')->where('subject_id', $id)->pluck('student_id')->toArray();
        return response()->json($assignedStudents);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        $undeletedSubjects = [];

        foreach ($ids as $id) {
            $subject = Subject::with('students')->find($id);
            if ($subject->students->isEmpty()) {
                $subject->delete();
            } else {
                $undeletedSubjects[] = $subject->name;
            }
        }

        if (empty($undeletedSubjects)) {
            return response()->json(['success' => 'Selected subjects have been deleted successfully.']);
        } else {
            return response()->json(['warning' => 'Some subjects were not deleted because they have assigned to Students: ' . implode(', ', $undeletedSubjects)]);
        }
    }
}

// ->skip($request->input('skip'))
// ->take($request->input('take'))
// ->get();
