<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination;
    public $query="";
    public $deleteId = null;

    public function search()
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if($this->deleteId){
            Service::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;

            session()->flash('success', 'Layanan berhasil dihapus!');

            return $this->redirectRoute('admin.service', navigate:true);
        }
    }

    public function render()
    {
        $services = Service::with('category')->orderBy('created_at', 'desc')->where('name', 'like', "%{$this->query}%")->paginate(5);
        return view('livewire.admin.service.service-index',[
            'services' => $services
        ]);
    }
}
