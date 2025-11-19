<?php

namespace App\Livewire\Admin\Barbershops;

use App\Models\Barbershop;
use Livewire\Component;
use Livewire\WithPagination;

class BarbershopIndex extends Component
{
    use WithPagination;

    public $deleteId = null; // ID user yang akan dihapus
    public $query = "";

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if ($this->deleteId){
            Barbershop::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;

            session()->flash('success', 'Barbershop berhasil dihapus!');
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $barbershops = Barbershop::orderBy('created_at', 'desc')->where('name', 'like', "%{$this->query}%")->paginate('5');

        return view('livewire.admin.barbershops.barbershop-index',[
            'barbershops' => $barbershops
        ]);
    }
}
