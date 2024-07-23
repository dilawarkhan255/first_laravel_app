<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('show_url', function($row) {
                    return route('roles.show', $row->id); // Assuming you have a show route
                })
                ->addColumn('edit_url', function($row) {
                    return route('roles.edit', $row->id);
                })
                ->addColumn('delete_url', function($row) {
                    return route('roles.destroy', $row->id);
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('roles.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>' .
                           '<form action="' . route('roles.destroy', $row->id) . '" method="POST" style="display:inline;">' .
                           csrf_field() .
                           method_field('DELETE') .
                           '<button type="submit" class="delete btn btn-danger btn-sm">Delete</button>' .
                           '</form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
