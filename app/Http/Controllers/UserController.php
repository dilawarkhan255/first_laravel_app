<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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

                if (in_array('list-users', $allPermissions)) {
                    $this->middleware('permission:list-users', ['only' => ['index']]);
                }

                if (in_array('create-users', $allPermissions)) {
                    $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
                }

                if (in_array('edit-users', $allPermissions)) {
                    $this->middleware('permission:edit-users', ['only' => ['edit', 'update']]);
                }

                if (in_array('delete-users', $allPermissions)) {
                    $this->middleware('permission:delete-users', ['only' => ['destroy']]);
                }

                if (empty(array_intersect(['list-users', 'create-users', 'edit-users', 'delete-users'], $allPermissions))) {
                    abort(403, 'Unauthorized action.');
                }
            }

            return $next($request);
        });
    }


    public function uploadImage(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $attachment = (new AttachmentController())->uploadSingle($file,auth()->user()->id,'User','profile');
            return back()->with('success', 'Profile image uploaded successfully.');
        }

        return back()->with('error', 'Profile image upload failed.');
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('parent_id',auth()->user()->id)->with('roles')->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('roles', function($user) {
                return $user->getRoleNames()->toArray();
            })
                ->addIndexColumn()
                ->addColumn('show_url', function($row) {
                    return route('users.show', $row->id);
                })
                ->addColumn('edit_url', function($row) {
                    return route('users.edit', $row->id);
                })
                ->addColumn('delete_url', function($row) {
                    return route('users.destroy', $row->id);
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('users.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>' .
                           '<form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline;">' .
                           csrf_field() .
                           method_field('DELETE') .
                           '<button type="submit" class="delete btn btn-danger btn-sm">Delete</button>' .
                           '</form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return view('users.create', compact('permissions', 'roles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // Validate the request (already handled by StoreUserRequest)
        $validatedData = $request->validated();

        // Prepare the input data, including the parent ID
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['parent_id'] = auth()->user()->id; // Capture the parent ID

        // Create a new user
        $user = User::create($input);

        // Assign the role to the user
        $role = Role::find($request->role_id);
        if ($role) {
            $user->assignRole($role->name);
        }

        // Assign permissions to the user
        if ($request->filled('permission')) {
            // Fetch selected permissions or select all if 'all' is chosen
            if (in_array('all', $request->input('permission'))) {
                $permissions = Permission::all();
            } else {
                $permissions = Permission::whereIn('id', $request->input('permission'))->get();
            }

            // Sync the user's permissions if any were selected
            $user->syncPermissions($permissions);
        }

        // Redirect to the users index page with a success message
        return redirect()->route('users.index')
                         ->withSuccess('New user added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $userRole = $user->roles->first();

        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            // 'permissions' validation rule removed
        ]);

        // Prepare the input data, handling the password separately
        $input = $request->except(['password', 'permissions']);
        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        }

        // Update the user's information
        $user->update($input);

        // Update the user's role
        $role = Role::find($request->role_id);
        if ($role) {
            $user->syncRoles([$role->name]);

            // Update the user's permissions based on the selected role
            if ($request->filled('permissions')) {
                $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
                $user->syncPermissions($permissions);
            } else {
                $user->syncPermissions([]); // Clear all permissions if none are selected
            }
        }

        // Redirect back with a success message
        return redirect()->route('users.index')
                         ->withSuccess('User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')
                ->withSuccess('User deleted successfully');
    }
}
