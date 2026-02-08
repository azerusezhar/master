<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan data dengan pagination + fitur search.
     */
    public function index(Request $request)
    {
        $query = MasterData::query();

        // Fitur pencarian
        if ($request->filled('search')) {
            $query->cari($request->search);
        }

        // Fitur filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        return view('master.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode'      => 'required|string|max:50|unique:master_data,kode',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        MasterData::create($validated);

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterData $master)
    {
        return view('master.show', compact('master'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterData $master)
    {
        return view('master.edit', compact('master'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterData $master)
    {
        // Validasi input (kecualikan kode milik sendiri untuk unique check)
        $validated = $request->validate([
            'kode'      => 'required|string|max:50|unique:master_data,kode,' . $master->id,
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $master->update($validated);

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterData $master)
    {
        $master->delete();

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil dihapus!');
    }
}
