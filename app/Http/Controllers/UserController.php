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
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:list-users|create-users|edit-users|delete-users');
        $this->middleware('permission:create-users');
        $this->middleware('permission:edit-users');
        $this->middleware('permission:delete-users');
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
            $data = User::with('roles')->get();
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

        return view('users.index', [
            'users' => User::latest('id')->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $role = DB::table('roles')->find($request->role_id);
        if($role){
            $user->assignRole($role->name);
        }

        return redirect()->route('users.index')
                ->withSuccess('New user is added successfully.');
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

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }

        $user->update($input);

        // Fetch the role using the Role model
        $role = Role::find($request->role_id);
        if ($role) {
            $user->syncRoles([$role]); // Ensure correct role synchronization
        }

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
