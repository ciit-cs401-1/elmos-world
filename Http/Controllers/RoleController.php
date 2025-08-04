<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'formal_name' => 'required|max:255',
            'description' => 'max:255'
        ]);

        $validateData['role_name'] = substr($validateData['formal_name'], 0, 1);

        $role = Role::create($validateData);
        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        return view('roles.show', ['role' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'formal_name' => 'required|max:255',
            'description' => 'max:255'
        ]);
        $validateData['role_name'] = substr($validateData['formal_name'], 0, 1);

        $role = Role::find($id);
        $role->update($validateData);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        try {
            $role->delete();
            return redirect()
                ->route('roles.index')
                ->with('success', 'Role deleted successfully');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
