<?php

namespace App\Livewire\Customer\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    use WithFileUploads;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $oldImage;
    public $image;

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
            'image'    => 'nullable|image|max:2048', // max 2MB
        ];
    }

    public function mount()
    {
        $user = Auth::user();

        $this->userId = $user->id;   
        $this->name = $user->name;   
        $this->email = $user->email;   
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


        $user->save();

        session()->flash('success', 'Profile berhasil diperbarui!');
        return $this->redirectRoute('customer.profile', navigate:true);
    }


    #[Layout('components.layouts.cust-app')]
    public function render()
    {
        return view('livewire.customer.profile.edit');
    }
}
