<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use App\Models\ServiceCategory;
use Livewire\Component;

class ServiceCreate extends Component
{
    public $service_category_id;
    public $name;
    public $price;

    protected $rules = [
        'service_category_id' => 'required|exists:service_categories,id',
        'name' => 'required|string',
        'price' => 'required|integer|min:1000',
    ];

    public function save()
    {
        $this->validate();

        Service::create([
            'service_category_id' => $this->service_category_id,
            'name' => $this->name,
            'price' => $this->price,
        ]);

        $this->reset();
        
        session()->flash('success', 'Layanan berhasil ditambahkan!');

        return $this->redirectRoute('admin.service', navigate:true);
    }

    public function render()
    {
        $category = ServiceCategory::all();
        return view('livewire.admin.service.service-create', [
            'categories' => $category
        ]);
    }
}
