<?php
namespace App\Livewire\Security;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleForm extends Component
{
    public $showModal = false;
    public $title;
    public $roleId;

    protected $rules = [
        'title' => 'required|string|unique:roles,name',
    ];

    public function openModal($id = null)
    {
        if ($id) {
            $role = Role::findOrFail($id);
            $this->title = $role->name;
            $this->roleId = $id;
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->title]);
        } else {
            Role::create([
                'name' => $this->title,
                'guard_name' => 'web'
            ]);
        }

        $this->resetForm();
        $this->dispatch('refresh-matrix');
        session()->flash('message', 'Role saved successfully');
    }

    private function resetForm()
    {
        $this->reset(['title', 'roleId', 'showModal']);
    }

    public function render()
    {
        return view('livewire.role-form');
    }
}