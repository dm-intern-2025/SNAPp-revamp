<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|unique:roles,name',
    ];

    public function save()
    {
        $this->validate();

        Role::create(['name' => $this->name]);

        session()->flash('message', 'Role created successfully.');

        $this->reset('name');

        // Close the modal if using wire-elements/modal
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.create-role');
    }
}
