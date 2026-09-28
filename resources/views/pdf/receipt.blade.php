<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $receipt['id'] }} | Hideout Coffee</title>
    <style>
        @page { margin: 4mm 5mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.35;
            color: #000;
            width: 70mm;
            margin: 0 auto;
        }
        .center { text-align: center; }
        .brand { font-size: 16px; font-weight: bold; letter-spacing: 1px; margin: 0 0 2px; }
        .logo { width: 60px; height: auto; margin: 0 auto 4px; }
        .sub { margin: 1px 0; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; margin: 0; }
        table td { padding: 0; vertical-align: top; }
        table td.l { text-align: left; }
        table td.r { text-align: right; white-space: nowrap; }
        .item-name { font-weight: bold; }
        .ind { display: inline-block; width: 12px; }
        .total-row td { font-size: 13px; font-weight: bold; }
        .sep td { border-top: 1px dashed #000; padding-top: 4px; }
        .thanks { margin-top: 8px; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-ho.png');
        $logoData = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;
    @endphp

    @if ($logoData)
        <link rel="icon" href="{{ $logoData }}">
    @endif

    <div class="center">
        @if ($logoData)
            <img src="{{ $logoData }}" class="logo" alt="">
        @endif
        <p class="brand">HIDEOUT COFFEE</p>
        <p class="sub">Transaksi #{{ $receipt['id'] }}</p>
        <p class="sub">{{ $receipt['code'] }}</p>
        <p class="sub">{{ $receipt['created_at'] }}</p>
        <p class="sub">Kasir: {{ $receipt['cashier'] }}</p>
    </div>

    <div class="divider"></div>

    <table>
        @foreach ($receipt['items'] as $item)
            <tr>
                <td class="l item-name">{{ $item['name'] }} x{{ $item['quantity'] }}</td>
                <td class="r">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
            </tr>
            @if ($item['discount'] > 0)
                <tr>
                    <td class="l"><span class="ind">Diskon</span></td>
                    <td class="r">-Rp {{ number_format($item['discount'], 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td class="l"><span class="ind">Subtotal</span></td>
                <td class="r">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
            </tr>
            @if (!$loop->last)
                <tr><td colspan="2" style="height:4px;"></td></tr>
            @endif
        @endforeach
        <tr class="sep"><td colspan="2"></td></tr>

        <tr>
            <td class="l">Subtotal</td>
            <td class="r">Rp {{ number_format($receipt['subtotal'], 0, ',', '.') }}</td>
        </tr>
        @if ($receipt['item_discounts_total'] > 0)
            <tr>
                <td class="l">Diskon Item</td>
                <td class="r">-Rp {{ number_format($receipt['item_discounts_total'], 0, ',', '.') }}</td>
            </tr>
        @endif
        @foreach ($receipt['promos'] as $promo)
            <tr>
                <td class="l">{{ $promo['name'] }}</td>
                <td class="r">-Rp {{ number_format($promo['discount_amount'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        @if ($receipt['manual_discount'] > 0)
            <tr>
                <td class="l">Diskon Manual</td>
                <td class="r">-Rp {{ number_format($receipt['manual_discount'], 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="sep"><td colspan="2"></td></tr>

        <tr class="total-row">
            <td class="l">TOTAL</td>
            <td class="r">Rp {{ number_format($receipt['total'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="l">Bayar</td>
            <td class="r">Rp {{ number_format($receipt['amount_paid'], 0, ',', '.') }}</td>
        </tr>
        @if ($receipt['change'] > 0)
            <tr>
                <td class="l">Kembali</td>
                <td class="r">Rp {{ number_format($receipt['change'], 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td class="l">Metode</td>
            <td class="r" style="text-transform:uppercase;">{{ $receipt['payment_method'] }}</td>
        </tr>
        <tr class="sep"><td colspan="2"></td></tr>
    </table>

    <p class="thanks">Terima kasih atas kunjungan Anda!</p>
</body>
</html>
