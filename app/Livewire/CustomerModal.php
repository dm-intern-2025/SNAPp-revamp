<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CustomerModal extends Component
{
    public $isOpen = false;

    protected $listeners = ['openModal' => 'open', 'closeModal' => 'close'];

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.customer-modal');
    }
}
