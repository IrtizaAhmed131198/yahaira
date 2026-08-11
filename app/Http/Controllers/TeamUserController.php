<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class TeamUserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('team-and-user-management', compact('roles'));
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->roles->count() > 0 ? ucfirst($row->roles->first()->name) : 'No Role';
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-success">Active</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editUser"><i class="fa-solid fa-pen"></i></a> ';
                    $btn = $btn.' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteUser"><i class="fa-solid fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($request->role);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'created user',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'description' => 'Created user ' . $user->name . ' with role ' . $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully'
        ]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated user',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'description' => 'Updated user ' . $user->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $userName = $user->name;
        $user->delete();
        
        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'deleted user',
            'subject_type' => User::class,
            'subject_id' => $id,
            'description' => 'Deleted user ' . $userName
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
