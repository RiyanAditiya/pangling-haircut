<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    use WithFileUploads;
    public $roles;

    #[Validate('required|min:3|string')]
    public $name='';

    
    #[Validate('required|email:dns|unique:users')]
    public $email='';

    #[Validate('required|min:6')]
    public $password='';

    #[Validate('required|exists:roles,id')]
    public $role;

    #[Validate('nullable|image|max:2048|mimes:jpg,jpeg,png')]
    public $image;


    public function mount()
    {
        $this->roles = Role::pluck('name', 'id');
    }

    public function save()
    {
        $validated = $this->validate();

        if($this->image){
            $validated['image'] = $this->image->store('img', 'public');
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'image' => $validated['image'],
        ]);

        $role = Role::findById($this->role);
        $user->assignRole($role);

        $this->reset();
        session()->flash('success', 'User berhasil ditambahkan!');

        return $this->redirectRoute('admin.users', navigate:true);
    }


    public function render()
    {
        return view('livewire.admin.users.create-user');
    }
}
