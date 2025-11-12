<?php

namespace App\Livewire\Admin\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;

class TransaksiIndex extends Component
{
    use WithPagination;

    public $query = '';
    public $deleteId = null; 
 

    public function search(): void
    {
        $this->resetPage();
    }

    public function updatingQuery(): void
    {
        $this->resetPage();
    }

    /**
     * Konfirmasi penghapusan.
     */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    /**
     * Hapus transaksi.
     */
    public function delete()
    {
        if ($this->deleteId) {
            try {
                Transaction::findOrFail($this->deleteId)->delete();
                session()->flash('success', 'Transaksi berhasil dihapus.');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus transaksi.');
            }
        }

        $this->deleteId = null;
        $this->resetPage();
    }

    public function render()
    {
       $transactions = Transaction::with(['booking', 'customer', 'barber', 'barbershop']);

        // Logika Pencarian berdasarkan $this->query
        if ($this->query) {
            $keyword = '%' . $this->query . '%';
            $transactions->where(function ($q) use ($keyword) {
                // Cari berdasarkan nama customer manual (kolom customer_name)
                $q->where('customer_name_manual', 'like', $keyword)
                  // atau cari berdasarkan nama customer dari relasi (untuk booking)
                  ->orWhereHas('customer', function ($customerQ) use ($keyword) {
                      $customerQ->where('name', 'like', $keyword);
                  });
            });
        }
        
        // Urutkan berdasarkan tanggal terbaru dan Pagination (menggunakan limit '5')
        $transactions = $transactions->latest('transaction_date')
            ->paginate('5');

        return view('livewire.admin.transactions.transaksi-index', [
            'transactions' => $transactions,
        ]);
    }
}
