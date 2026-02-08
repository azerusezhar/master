<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        /* ============================================
           STYLE PDF - Simple & Clean untuk Print
           ============================================ */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        
        .info-box {
            margin-bottom: 15px;
            padding: 10px;
            background: #f5f5f5;
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
            font-weight: bold;
        }
        
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-row {
            font-weight: bold;
            background: #e0e0e0 !important;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .footer .ttd {
            margin-top: 60px;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    {{-- Header Laporan --}}
    <div class="header">
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}</p>
    </div>

    {{-- Info Box --}}
    <div class="info-box">
        <strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}<br>
        <strong>Total Transaksi:</strong> {{ $transaksis->count() }} transaksi
    </div>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 20%">Kode Transaksi</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 25%">User</th>
                <th style="width: 10%">Items</th>
                <th class="text-right" style="width: 25%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $trx->user->name }}</td>
                    <td class="text-center">{{ $trx->items->count() }}</td>
                    <td class="text-right currency">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
            
            {{-- Total Keseluruhan --}}
            @if($transaksis->count() > 0)
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right currency">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Footer TTD --}}
    <div class="footer">
        <p>{{ config('app.name', 'Laravel') }}, {{ now()->format('d F Y') }}</p>
        <div class="ttd">
            <p>_______________________</p>
            <p>Petugas</p>
        </div>
    </div>
</body>
</html>
