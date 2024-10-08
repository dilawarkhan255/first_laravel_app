<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
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

            if (in_array('create-students', $allPermissions)) {
                $this->middleware('permission:create-students', ['only' => ['create', 'store']]);
            }

            if (in_array('edit-students', $allPermissions)) {
                $this->middleware('permission:edit-students', ['only' => ['edit', 'update']]);
            }

            if (in_array('delete-students', $allPermissions)) {
                $this->middleware('permission:delete-students', ['only' => ['destroy']]);
            }

            if (in_array('students', $allPermissions) ||
                array_intersect(['create-students', 'edit-students', 'delete-students'], $allPermissions)) {
                $this->middleware('permission:students|create-students|edit-students|delete-students', ['only' => ['index']]);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        return $next($request); // Proceed with the request
    });
}


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

            $subjects = Subject::select('id','name')->get()->toArray();
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

    public function show($id)
    {
        $student = Student::with('subjects')->find($id);
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
        $student->subjects()->attach($request->subjects);
        Session::flash('success', 'Subjects assigned successfully.');

        return response()->json(['success' => true]);
    }


    public function unassignSubjects(Request $request)
    {
        $student = Student::find($request->student_id);
        $student->subjects()->detach($request->subject_id);
        return response()->json(['success' => true]);
    }

    public function getAvailableSubjects($id)
    {
        $assignedSubjects = DB::table('student_subject')->where('student_id', $id)->pluck('subject_id')->toArray();
        return response()->json($assignedSubjects);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        $undeletedStudents = [];

        foreach ($ids as $id) {
            $student = Student::with('subjects')->find($id);
            if ($student->subjects->isEmpty()) {
                $student->delete();
            } else {
                $undeletedStudents[] = $student->name;
            }
        }

        if (empty($undeletedStudents)) {
            return response()->json(['success' => 'Selected students have been deleted successfully.']);
        } else {
            return response()->json(['warning' => 'Some students were not deleted because they have subjects assigned: ' . implode(', ', $undeletedStudents)]);
        }

    }

}

