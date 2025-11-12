<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EditUser extends Component
{
    use WithFileUploads;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;
    public $oldImage;
    public $image;
    public $roles;

    // Aturan validasi
    protected function rules()
    {
        return [
            'name'  => 'required|string|min:3|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId), // email harus unik kecuali untuk user ini
            ],
            'password' => 'nullable|min:6',
            'role'     => 'required|exists:roles,name',
            'image'    => 'nullable|image|max:2048', // max 2MB
        ];
    }

    public function mount($id)
    {
        $this->roles = Role::pluck('name', 'name');
        $user = User::findOrFail($id);

        $this->userId = $user->id;   
        $this->name = $user->name;   
        $this->email = $user->email;   
        $this->role = $user->roles->first()?->name; 
        $this->oldImage = $user->image;   
    }

    public function update()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $user->name = $this->name;
        $user->email = $this->email;

        if(!empty($this->password)){
            $user->password = Hash::make($this->password);
        }

        if($this->image){
            $path = $this->image->store('img', 'public');
            $user->image = $path;
        }

         // sync role supaya role lama diganti dengan yang baru
        $user->syncRoles([$this->role]);

        $user->save();

        session()->flash('success', 'User berhasil diperbarui!');
        return $this->redirectRoute('admin.users', navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user');
    }
}
