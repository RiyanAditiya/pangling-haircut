<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithColumnFormatting
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        $transactions = Transaction::with(['customer', 'barber', 'barbershop'])
            ->whereBetween('transaction_date', [
                $this->startDate,
                Carbon::parse($this->endDate)->endOfDay(),
            ])
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'Tanggal'  => Carbon::parse($t->transaction_date)->format('d M Y H:i'),
                    'Customer' => $t->customer->name ?? $t->customer_name_manual,
                    'Barbershop'  => $t->barbershop->name,
                    'Layanan'  => $t->service_name,
                    'Barber'   => $t->barber->name ?? 'N/A',
                    'Tipe'     => ucfirst($t->type),
                    'Jumlah (Rp)' => (float) $t->amount, // ✅ angka mentah, bukan string
                ];
            });

        // Tambahkan total di baris terakhir
        $totalRevenue = $transactions->sum('Jumlah (Rp)');

        $transactions->push([
            'Tanggal'  => '',
            'Customer' => '',
            'Barbershop'  => '',
            'Layanan'  => '',
            'Barber'   => '',
            'Tipe'     => 'Total',
            'Jumlah (Rp)' => $totalRevenue,
        ]);

        return $transactions;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Customer', 'Barbershop', 'Layanan', 'Barber', 'Tipe', 'Jumlah (Rp)'];
    }

    public function title(): string
    {
        $start = Carbon::parse($this->startDate)->format('d-M');
        $end = Carbon::parse($this->endDate)->format('d-M');

        return substr("Laporan {$start}-{$end}", 0, 31);
    }

    /**
     * Format kolom di Excel agar kolom Jumlah ditampilkan sebagai angka (tanpa desimal)
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER, // ✅ Kolom F adalah “Jumlah (Rp)”
        ];
    }
}
