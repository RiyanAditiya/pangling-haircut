<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pangling Haircut</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }

        td.amount {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Laporan Transaksi Pangling Haircut</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Barbershop</th>
                <th>Layanan</th>
                <th>Barber</th>
                <th>Tipe</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->transaction_date->format('d M Y H:i') }}</td>
                    <td>{{ $t->customer->name ?? $t->customer_name_manual }}</td>
                    <td>{{ $t->service_name }}</td>
                    <td>{{ $t->barbershop->name }}</td>
                    <td>{{ $t->barber->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($t->type) }}</td>
                    <td class="amount">{{ number_format($t->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Transaksi:</strong> {{ number_format($totalTransactions) }}</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</body>

</html>
