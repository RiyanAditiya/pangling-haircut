<?php

namespace App\Livewire\Admin\Service;

use App\Models\ServiceCategory;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryCreate extends Component
{
    use WithFileUploads;

    #[Validate('required|unique:service_categories')]
    public $name;

    #[Validate('required')]
    public $description;

    #[Validate('required|image|max:2048|mimes:jpg,jpeg,png')]
    public $image;

    public function save()
    {
        $validated = $this->validate();

        if($this->image){
            $validated['image'] = $this->image->store('img', 'public');
        }

        ServiceCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $validated['image']
        ]);

        $this->reset();

        session()->flash('success', 'Kategori layanan berhasil ditambahkan!');

        return $this->redirectRoute('admin.category', navigate:true);
    }


    public function render()
    {
        return view('livewire.admin.service.category-create');
    }
}
