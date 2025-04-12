<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionMatrix extends Component
{
    // Matrix properties
    public $roles;
    public $permissions;
    public $selectedPermissions = [];

    // Form properties (for both role and permission forms)
    public $roleName;
    public $permissionName;
    public $editingId = null;
    public $formType = ''; // 'role' or 'permission'

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();

        // Initialize selectedPermissions
        foreach ($this->roles as $role) {
            foreach ($this->permissions as $permission) {
                $this->selectedPermissions[$role->id][$permission->id] = $role->hasPermissionTo($permission);
            }
        }
    }

    // Matrix actions
    public function savePermissions()
    {
        foreach ($this->roles as $role) {
            $permissionIds = collect($this->selectedPermissions[$role->id])
                ->filter(fn($value) => $value)
                ->keys();
            $role->syncPermissions($permissionIds);
        }
        session()->flash('message', 'Permissions updated successfully.');
    }

    // Role CRUD
    public function openRoleModal($id = null)
    {
        $this->formType = 'role';
        $this->editingId = $id;

        if ($id) {
            $role = Role::findOrFail($id);
            $this->roleName = $role->name;
        } else {
            $this->roleName = '';
        }
    }

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|unique:roles,name,' . $this->editingId
        ]);

        if ($this->editingId) {
            $role = Role::findOrFail($this->editingId);
            $role->update(['name' => $this->roleName]);
        } else {
            Role::create([
                'name' => $this->roleName,
                'guard_name' => 'web'
            ]);
        }

        $this->resetForm();
        $this->loadData();
        session()->flash('message', 'Role saved successfully.');
    }

    // Permission CRUD
    public function openPermissionModal($id = null)
    {
        $this->formType = 'permission';
        $this->editingId = $id;

        if ($id) {
            $permission = Permission::findOrFail($id);
            $this->permissionName = $permission->name;
        } else {
            $this->permissionName = '';
        }
    }

    public function savePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|unique:permissions,name,' . $this->editingId
        ]);

        if ($this->editingId) {
            $permission = Permission::findOrFail($this->editingId);
            $permission->update(['name' => $this->permissionName]);
        } else {
            Permission::create([
                'name' => $this->permissionName,
                'guard_name' => 'web'
            ]);
        }

        $this->resetForm();
        $this->loadData();
        session()->flash('message', 'Permission saved successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'roleName',
            'permissionName',
            'editingId',
            'formType'
        ]);
    }

    public function deleteRole($id)
    {
        Role::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'Role deleted.');
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'Permission deleted.');
    }
    
    public function editPermission($id)
{
    $this->formType = 'permission';
    $this->editingId = $id;

    $permission = Permission::findOrFail($id);
    $this->permissionName = $permission->name;
}

public function editRole($id)
{
    $this->formType = 'role';
    $this->editingId = $id;

    $role = Role::findOrFail($id);
    $this->roleName = $role->name;
}


    public function render()
    {
        return view('livewire.role-permission-matrix');
    }
}
