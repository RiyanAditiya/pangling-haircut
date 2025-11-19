<?php

namespace App\Livewire\Admin\Barbershops;

use App\Models\Barbershop;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BarbershopCreate extends Component
{
    #[Validate('required')]
    public $name;

    #[Validate('required|min:5')]
    public $address;

    #[Validate('required|min:10|unique:barbershops')]
    public $phone;

    public function save()
    {
        $this->validate();

        Barbershop::create([
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
        ]);

        $this->reset();
        session()->flash('success', 'Barbershop berhasil ditambahkan!');

        return $this->redirectRoute('admin.barbershop', navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.barbershops.barbershop-create');
    }
}
