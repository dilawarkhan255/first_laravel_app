<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = auth()->user();

            if ($user) {
                $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

                $userRoles = $user->roles;

                if ($userRoles->isNotEmpty()) {
                    $rolePermissions = $userRoles->map->permissions->flatten()->pluck('name')->unique()->toArray();
                } else {
                    $rolePermissions = [];
                }

                $allPermissions = array_merge($userPermissions, $rolePermissions);

                if (in_array('create-roles', $allPermissions)) {
                    $this->middleware('permission:create-roles', ['only' => ['create', 'store']]);
                }

                if (in_array('edit-roles', $allPermissions)) {
                    $this->middleware('permission:edit-roles', ['only' => ['edit', 'update']]);
                }

                if (in_array('delete-roles', $allPermissions)) {
                    $this->middleware('permission:delete-roles', ['only' => ['destroy']]);
                }

                if (in_array('roles', $allPermissions)) {
                    $this->middleware('permission:roles', ['only' => ['index', 'store']]);
                } else {
                    // Deny access to 'index' and 'store' if no role-related permissions are present
                    abort(403, 'Unauthorized action.');
                }
            }

            return $next($request);
        });
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('show_url', function($row) {
                    return route('roles.show', $row->id);
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
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        if (in_array('all', $request->input('permission'))) {
            $permissions = Permission::all();
        } else {
            $permissions = Permission::whereIn('id', $request->input('permission'))->get();
        }
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
                        ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $rolePermissions = $role->permissions;
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            // Remove required from validation as requested
            // 'permission' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        // Ensure 'permission' is always an array
        $permissionsInput = $request->input('permission', []);

        // Check if 'all' is in the permissions input
        if (in_array('all', $permissionsInput)) {
            $permissions = Permission::all();
        } else {
            $permissions = Permission::whereIn('id', $permissionsInput)->get();
        }

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }


    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
