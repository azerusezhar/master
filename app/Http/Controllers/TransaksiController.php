<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'items']);

        // Filter tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(10)->withQueryString();

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = MasterData::aktif()->get();
        return view('transaksi.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * ============================================
     * CONTOH LOGIC TRANSAKSI MULTI-ITEM
     * dengan DB::transaction untuk data integrity
     * ============================================
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required|exists:master_data,id',
            'items.*.jumlah'  => 'required|integer|min:1',
            'items.*.harga'   => 'required|numeric|min:0',
        ]);

        // Gunakan DB::transaction untuk memastikan semua data tersimpan atau tidak sama sekali
        DB::transaction(function () use ($request) {
            // 1. Buat header transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'user_id'        => Auth::id(),
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
                'status'         => 'pending',
            ]);

            // 2. Loop setiap item dan simpan ke transaksi_items
            foreach ($request->items as $item) {
                $masterData = MasterData::find($item['id']);
                
                TransaksiItem::create([
                    'transaksi_id'   => $transaksi->id,
                    'master_data_id' => $masterData->id,
                    'nama_item'      => $masterData->nama, // Snapshot nama
                    'jumlah'         => $item['jumlah'],
                    'harga'          => $item['harga'],
                    // subtotal otomatis dihitung di model
                ]);
            }

            // 3. Hitung total transaksi
            $transaksi->hitungTotal();
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['user', 'items.masterData']);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('items');
        $items = MasterData::aktif()->get();
        return view('transaksi.edit', compact('transaksi', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'status'          => 'required|in:pending,selesai,batal',
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required|exists:master_data,id',
            'items.*.jumlah'  => 'required|integer|min:1',
            'items.*.harga'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            // Update header
            $transaksi->update([
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status'     => $request->status,
            ]);

            // Hapus items lama, buat ulang
            $transaksi->items()->delete();

            foreach ($request->items as $item) {
                $masterData = MasterData::find($item['id']);
                
                TransaksiItem::create([
                    'transaksi_id'   => $transaksi->id,
                    'master_data_id' => $masterData->id,
                    'nama_item'      => $masterData->nama,
                    'jumlah'         => $item['jumlah'],
                    'harga'          => $item['harga'],
                ]);
            }

            $transaksi->hitungTotal();
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete(); // Items otomatis terhapus (cascade)
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}
