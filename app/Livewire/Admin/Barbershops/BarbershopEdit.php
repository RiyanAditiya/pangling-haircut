<?php

namespace App\Livewire\Admin\Barbershops;

use App\Models\Barbershop;
use Livewire\Component;
use Illuminate\Validation\Rule;

class BarbershopEdit extends Component
{
    public $barbershopId;
    public $name;
    public $address;
    public $phone;

    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required|min:5',
            'phone' => [
                'required',
                'min:10',
                Rule::unique('barbershops')->ignore($this->barbershopId),
            ]
        ];
    }

    public function mount($id)
    {
        $barbershop = Barbershop::findOrFail($id);
        $this->barbershopId = $barbershop->id;
        $this->name = $barbershop->name;
        $this->address = $barbershop->address;
        $this->phone = $barbershop->phone;
    }

    public function update()
    {
        $this->validate();

        $barbershop = Barbershop::findOrFail($this->barbershopId);

        $barbershop->name = $this->name;
        $barbershop->address = $this->address;
        $barbershop->phone = $this->phone;

        $barbershop->save();

        session()->flash('success', 'Barbershop berhasil diubah!');

        return $this->redirectRoute('admin.barbershop', navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.barbershops.barbershop-edit');
    }
}
