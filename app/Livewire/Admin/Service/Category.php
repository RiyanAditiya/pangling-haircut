<?php

namespace App\Livewire\Admin\Service;

use App\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Category extends Component
{
    use WithPagination;
    public $query;
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
            ServiceCategory::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;

            session()->flash('success', 'Kategori layanan berhasil dihapus!');
        }
    }

    public function render()
    {
        $category = ServiceCategory::orderBy('created_at', 'desc')->where('name', 'like', "%{$this->query}%")->paginate('5');
        return view('livewire.admin.service.category',[
            'categories' => $category
        ]);
    }
}
