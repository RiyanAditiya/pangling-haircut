<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use Livewire\Component;
use App\Models\ServiceCategory;

class ServiceEdit extends Component
{
    public $serviceId;
    public $service_category_id;
    public $name;
    public $price;

    protected $rules = [
        'service_category_id' => 'required|exists:service_categories,id',
        'name' => 'required|string',
        'price' => 'required|integer|min:1000',
    ];

    public function mount($id)
    {
        $services = Service::findOrFail($id);
        $this->serviceId = $services->id;
        $this->service_category_id = $services->service_category_id;
        $this->name = $services->name;
        $this->price = $services->price;
    }

    public function update()
    {
        $this->validate();

        $services = Service::findOrFail($this->serviceId);
        
        $services->service_category_id = $this->service_category_id;
        $services->name = $this->name;
        $services->price = $this->price;

        $services->save();

        session()->flash('success', 'Layanan berhasil diperbarui!');

        return $this->redirectRoute('admin.service', navigate:true);
    }

    public function render()
    {
        $category = ServiceCategory::all();
        return view('livewire.admin.service.service-edit',[
            'categories' => $category
        ]);
    }
}
