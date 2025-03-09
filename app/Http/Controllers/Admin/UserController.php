<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get(['id', 'name', 'email']);

        $contestants = User::whereHas('roles', function ($query) {
            $query->where('name', 'contestant');
        })->get(['id', 'name', 'email']);

        return view('admin.users.index', compact('judges', 'contestants'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(User $user): RedirectResponse
    {
        // Check if the user has the "Admin" role
        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->back()->with('error', 'You cannot delete an Admin user.');
        }

        // Proceed to delete the user if not an admin
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }


    public function addJudge(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // ✅ Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Automatically Assign the "Judge" Role
        $role = Role::where('name', 'judge')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('admin.users.index')->with('success', 'Judge added successfully!');
    }

    public function addContestant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // ✅ Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Automatically Assign the "Judge" Role
        $role = Role::where('name', 'contestant')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('admin.users.index')->with('success', 'Contestant added successfully!');
    }


}
