<?php

// Livewire Component
namespace App\Http\Livewire;

use Livewire\Component;

class CustomerModal extends Component
{
    public $showModal = false;
    public $showSuccessModal = false;
    public $name;
    public $email;
    public $customer_id;
    public $isSubmitting = false;

    protected $listeners = ['openCustomerModal' => 'open'];

    public function open()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
        $this->showSuccessModal = false;
    }

    protected function resetForm()
    {
        $this->reset(['name', 'email', 'customer_id', 'isSubmitting']);
        $this->resetErrorBag();
    }

    

    public function render()
    {
        return view('livewire.customer-modal');
    }
}