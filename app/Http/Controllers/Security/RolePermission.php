<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermission extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::get();
        $permissions = Permission::get();
        return view('role-permissions.role-permissions', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $permissionsByRole = [];
    
        // Gather selected permissions for each role
        foreach ($request->input('permission', []) as $roleId => $permissionIds) {
            foreach ($permissionIds as $permissionId) {
                $permissionsByRole[$roleId][] = $permissionId;
            }
        }
    
        // Update permissions for each role
        foreach ($permissionsByRole as $roleId => $permissionIds) {
            $role = Role::find($roleId);
            $role->syncPermissions($permissionIds);
        }
    
        // Clear permissions for roles with no checkboxes
        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            if (!isset($permissionsByRole[$role->id])) {
                $role->syncPermissions([]);
            }
        }
    
        // Return to the view with success message and updated roles & permissions
        $roles = Role::all();
        $permissions = Permission::all();
        return view('role-permissions.role-permissions', compact('roles', 'permissions'))
            ->with('success', 'Permissions updated successfully.');
    }
    

}
