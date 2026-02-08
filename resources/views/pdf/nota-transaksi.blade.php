<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <style>
        /* ============================================
           STYLE NOTA/INVOICE - Simple & Clean
           ============================================ */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .header .company {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .info-col p {
            margin: 3px 0;
        }
        
        .info-col strong {
            display: inline-block;
            width: 100px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        table th {
            background: #333;
            color: #fff;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            width: 300px;
            margin-left: auto;
        }
        
        .total-section table {
            border: none;
        }
        
        .total-section td {
            border: none;
            padding: 5px 8px;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000 !important;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #ffeeba; color: #856404; }
        .status-selesai { background: #c3e6cb; color: #155724; }
        .status-batal { background: #f5c6cb; color: #721c24; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="company">{{ config('app.name', 'NAMA TOKO/PERUSAHAAN') }}</div>
        <h1>NOTA TRANSAKSI</h1>
        <p>{{ $transaksi->kode_transaksi }}</p>
    </div>

    {{-- Info Transaksi --}}
    <div class="info-row">
        <div class="info-col">
            <p><strong>Tanggal:</strong> {{ $transaksi->tanggal->format('d F Y') }}</p>
            <p><strong>Petugas:</strong> {{ $transaksi->user->name }}</p>
        </div>
        <div class="info-col" style="text-align: right;">
            <p>
                <strong>Status:</strong> 
                <span class="status-badge status-{{ $transaksi->status }}">
                    {{ ucfirst($transaksi->status) }}
                </span>
            </p>
        </div>
    </div>

    @if($transaksi->keterangan)
        <p><strong>Keterangan:</strong> {{ $transaksi->keterangan }}</p>
    @endif

    {{-- Tabel Items --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 40%">Nama Item</th>
                <th class="text-center" style="width: 10%">Qty</th>
                <th class="text-right" style="width: 20%">Harga</th>
                <th class="text-right" style="width: 25%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_item }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total Section --}}
    <div class="total-section">
        <table>
            <tr>
                <td>Subtotal ({{ $transaksi->items->count() }} item):</td>
                <td class="text-right currency">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
            </tr>
            {{-- Tambahkan diskon/pajak di sini jika perlu --}}
            {{-- 
            <tr>
                <td>Diskon:</td>
                <td class="text-right currency">- Rp 0</td>
            </tr>
            <tr>
                <td>Pajak (11%):</td>
                <td class="text-right currency">+ Rp 0</td>
            </tr>
            --}}
            <tr class="grand-total">
                <td>TOTAL:</td>
                <td class="text-right currency">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Terima kasih atas transaksi Anda.</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
