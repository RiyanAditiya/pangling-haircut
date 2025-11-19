<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use \Livewire\WithPagination;


class DataUser extends Component
{
    use WithPagination;

    public $deleteId = null; // ID user yang akan dihapus
    public $query = "";


    public function search()
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

   
    // Set ID user yang ingin dihapus
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    // Hapus user
    public function delete()
    {
        if ($this->deleteId) {
            User::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;

            session()->flash('success', 'User berhasil dihapus!');
        }
    }

    public function render()
    {
        $users = User::orderBy('created_at', 'desc')->where('name', 'like', "%{$this->query}%")->paginate(5);
        return view('livewire.admin.users.data-user', [
            'users' => $users
        ]);
    }

   
}
