<?php
namespace App\Livewire\Security;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionForm extends Component
{
    public $showModal = false;
    public $title;
    public $permissionId;

    protected $rules = [
        'title' => 'required|string|unique:permissions,name',
    ];

    public function openModal($id = null)
    {
        if ($id) {
            $permission = Permission::findOrFail($id);
            $this->title = $permission->name;
            $this->permissionId = $id;
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->permissionId) {
            $permission = Permission::findOrFail($this->permissionId);
            $permission->update(['name' => $this->title]);
        } else {
            Permission::create([
                'name' => $this->title,
                'guard_name' => 'web'
            ]);
        }

        $this->resetForm();
        $this->dispatch('refresh-matrix');
        session()->flash('message', 'Permission saved successfully');
    }

    private function resetForm()
    {
        $this->reset(['title', 'permissionId', 'showModal']);
    }

    public function render()
    {
        return view('livewire..permission-form');
    }
}