<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{


    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        // Return a view for creating a new role
        return view('roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|unique:roles,name|max:255', // Role name must be unique and not exceed 255 characters
        ]);

        // Create a new role in the database
        $role = Role::create([
            'name' => $request->name,
        ]);

        // Redirect to the roles index page with success message
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        // Return a view to display the details of a specific role
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        // Return a view to edit the specified role
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255', // Ensure the role name is unique, except for the current role
        ]);

        // Update the role in the database
        $role->update([
            'name' => $request->name,
        ]);

        // Redirect to the roles index page with success message
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Delete the role from the database
        $role->delete();

        // Redirect to the roles index page with success message
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}

