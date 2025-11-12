<?php

namespace App\Livewire\Admin\Report;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportIndex extends Component
{
    use WithPagination;

    // --- Properti Filter ---
    // Atur default ke awal dan akhir bulan ini untuk memudahkan
    public $startDate; 
    public $endDate;

    // --- Hasil Filter ---
    public $totalRevenue = 0;
    public $totalTransactions = 0;

    protected $listeners = ['refreshReport' => '$refresh']; // Untuk me-refresh dari luar

    public function mount()
    {
        // Inisialisasi tanggal: awal dan akhir hari ini, atau awal/akhir bulan
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfDay()->toDateString();
        
        $this->loadSummary();
    }

    public function updated($field)
    {
        // Muat ulang ringkasan setiap kali tanggal berubah
        if (in_array($field, ['startDate', 'endDate'])) {
            $this->loadSummary();
            $this->resetPage(); // Reset pagination saat filter berubah
        }
    }

    /**
     * Menghitung total ringkasan pendapatan dan jumlah transaksi
     */
    public function loadSummary()
    {
        // Validasi dasar
        if (!$this->startDate || !$this->endDate) {
            $this->totalRevenue = 0;
            $this->totalTransactions = 0;
            return;
        }

        // Pastikan tanggal akhir mencakup seluruh hari (hingga 23:59:59)
        $endDateTime = Carbon::parse($this->endDate)->endOfDay();

        $filteredTransactions = Transaction::whereBetween('transaction_date', [
            $this->startDate,
            $endDateTime,
        ]);

        $this->totalRevenue = $filteredTransactions->sum('amount');
        $this->totalTransactions = $filteredTransactions->count();
    }

    /**
     * Mengambil data transaksi yang difilter
     */
    public function getFilteredTransactions()
    {
        // Pastikan tanggal akhir mencakup seluruh hari
        $endDateTime = Carbon::parse($this->endDate)->endOfDay();
        
        return Transaction::with(['customer', 'barber', 'barbershop'])
            ->whereBetween('transaction_date', [
                $this->startDate,
                $endDateTime,
            ])
            ->latest('transaction_date')
            ->paginate(15);
    }
    
    /**
     * Fungsi yang akan dipanggil saat tombol export di-klik.
     * Fungsi ini TIDAK AKAN langsung menghasilkan file, tetapi akan MENGIRIM data.
     */

   // Export ke Excel
    public function exportExcel()
    {
        $fileName = 'laporan-transaksi-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new TransactionsExport($this->startDate, $this->endDate),
            $fileName
        );
    }

    // Export ke PDF
    public function exportPdf()
    {
        $transactions = Transaction::with(['customer', 'barber', 'barbershop'])
            ->whereBetween('transaction_date', [
                $this->startDate,
                Carbon::parse($this->endDate)->endOfDay(),
            ])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('exports.transactions-pdf', [
            'transactions' => $transactions,
            'totalTransactions' => $transactions->count(),
            'totalRevenue' => $transactions->sum('amount'),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(fn() => print($pdf->output()), 'laporan-transaksi.pdf');
    }


    
    public function render()
    {
        $transactions = $this->getFilteredTransactions();

        return view('livewire.admin.report.report-index', [
            'transactions' => $transactions
        ]);
    }
}
