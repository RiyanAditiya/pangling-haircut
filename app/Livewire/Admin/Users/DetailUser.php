<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class DetailUser extends Component
{
    public $user;

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
        $this->user->getRoleNames()->first();
    }

    public function render()
    {
        return view('livewire.admin.users.detail-user', [
            'user' => $this->user
        ]);
    }
}
