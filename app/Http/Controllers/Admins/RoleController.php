<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Inertia::render('Admins/Roles/Index', [
            'roles' => Role::with('permissions')->paginate(5),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => ['required', 'max:25', 'unique:roles'],
            'permissions' => 'required'
        ]);
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        if ($request->has('permissions')) {
            $role->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role) {
        $this->validate($request, [
            'name' => ['required', 'max:25'],
            'permissions' => 'required'
        ]);
        if ($request->has('permissions')) {
            $role->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }
        $role->syncPermissions(collect($request->permissions)->pluck('id')->toArray());
        $role->update(['name' => $request->name]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
        $role->delete();
        return back();
    }
}
