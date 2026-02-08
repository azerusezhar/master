<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman filter laporan.
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Generate PDF Laporan Transaksi.
     */
    public function transaksiPdf(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        // Ambil data transaksi berdasarkan filter
        $transaksis = Transaksi::with(['user', 'items'])
            ->whereDate('tanggal', '>=', $request->dari_tanggal)
            ->whereDate('tanggal', '<=', $request->sampai_tanggal)
            ->where('status', 'selesai')
            ->orderBy('tanggal')
            ->get();

        $totalKeseluruhan = $transaksis->sum('total');

        // Generate PDF
        $pdf = Pdf::loadView('pdf.laporan-transaksi', [
            'transaksis'       => $transaksis,
            'totalKeseluruhan' => $totalKeseluruhan,
            'dariTanggal'      => $request->dari_tanggal,
            'sampaiTanggal'    => $request->sampai_tanggal,
        ]);

        // Return PDF (stream = preview, download = langsung download)
        return $pdf->stream('laporan-transaksi.pdf');
        // atau: return $pdf->download('laporan-transaksi.pdf');
    }

    /**
     * Generate PDF Detail Transaksi (Nota/Invoice).
     */
    public function notaPdf(Transaksi $transaksi)
    {
        $transaksi->load(['user', 'items.masterData']);

        $pdf = Pdf::loadView('pdf.nota-transaksi', [
            'transaksi' => $transaksi,
        ]);

        return $pdf->stream("nota-{$transaksi->kode_transaksi}.pdf");
    }
}
