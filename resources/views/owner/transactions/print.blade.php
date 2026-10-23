<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Transaksi | Hideout Coffee</title>
    <style>
        @page { margin: 12mm 10mm; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; margin: 0; }

        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #764706; padding-bottom: 8px; }
        .brand h1 { font-size: 20px; color: #764706; margin: 0; }
        .brand p { margin: 2px 0 0; font-size: 10px; color: #5f4a3b; }
        .meta { text-align: right; font-size: 9px; color: #5f4a3b; }
        .meta p { margin: 2px 0; }

        h2 { text-align: center; font-size: 15px; color: #2b1f17; margin: 14px 0 4px; }
        .subtitle { text-align: center; font-size: 9px; color: #8b684b; margin: 0 0 12px; }

        .stats { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stats td { border: 1px solid #e0d6c8; padding: 6px 10px; text-align: center; background: #faf7f4; width: 25%; }
        .stats .label { font-size: 8px; text-transform: uppercase; font-weight: bold; color: #8b684b; }
        .stats .value { font-size: 14px; font-weight: bold; color: #2b1f17; }

        table.list { width: 100%; border-collapse: collapse; }
        table.list th { background: #764706; color: #fff; font-size: 9px; padding: 6px 8px; text-align: left; }
        table.list td { border: 1px solid #e0d6c8; padding: 5px 8px; font-size: 9px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .status-completed { color: #166534; }
        .status-cancelled { color: #b91c1c; }

        .footer { margin-top: 14px; display: flex; justify-content: space-between; font-size: 9px; color: #5f4a3b; border-top: 1px solid #e0d6c8; padding-top: 6px; }
        .empty { text-align: center; padding: 24px; color: #8b684b; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-ho.png');
        $logoData = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;
    @endphp

    <div class="header">
        <div class="brand">
            @if ($logoData)
                <img src="{{ $logoData }}" style="width:40px; height:auto; display:inline-block; vertical-align:middle; margin-right:6px;">
            @endif
            <span style="font-size:20px; font-weight:bold; color:#764706; vertical-align:middle;">HIDEOUT COFFEE</span>
        </div>
        <div class="meta">
            <p>Dicetak: {{ $printedAt }}</p>
            <p>Oleh: {{ $printedBy?->username }}</p>
        </div>
    </div>

    <h2>Laporan Data Transaksi</h2>
    <p class="subtitle">
        @if (count($filterSummary))
            {{ implode(' &bull; ', $filterSummary) }}
        @else
            Semua data transaksi
        @endif
    </p>

    <table class="stats">
        <tr>
            <td>
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($transactions->count(), 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Total Pendapatan (Selesai)</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Selesai</div>
                <div class="value">{{ number_format($completedTransactions, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Dibatalkan</div>
                <div class="value">{{ number_format($cancelledTransactions, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    @if ($transactions->isEmpty())
        <div class="empty">Tidak ada transaksi yang cocok dengan filter.</div>
    @else
        <table class="list">
            <thead>
                <tr>
                    <th class="center" style="width:30px;">No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kasir / Owner</th>
                    <th class="center">Metode</th>
                    <th class="right">Total</th>
                    <th class="center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $index => $transaction)
                    <tr {!! $loop->even ? 'style="background:#faf7f4;"' : '' !!}>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="bold">{{ $transaction->code }}</td>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td class="bold">{{ $transaction->customer_name ?? '-' }}</td>
                        <td>
                            @if ($transaction->user)
                                {{ trim($transaction->user->first_name.' '.$transaction->user->last_name) ?: $transaction->user->username }}
                                ({{ $transaction->user->hasRole('owner') ? 'Owner' : 'Kasir' }})
                            @else
                                -
                            @endif
                        </td>
                        <td class="center">{{ strtoupper($transaction->payment_method) }}</td>
                        <td class="right bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td class="center {{ $transaction->status === 'completed' ? 'status-completed' : 'status-cancelled' }}">
                            {{ $transaction->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <span>Hideout Coffee &mdash; Laporan Data Transaksi</span>
        <span>{{ number_format($transactions->count(), 0, ',', '.') }} transaksi ditampilkan</span>
    </div>
</body>
</html>
