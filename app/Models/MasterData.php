<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    /**
     * Kolom yang bisa diisi secara mass assignment.
     * Ganti sesuai kebutuhan tabel.
     */
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'status',
    ];

    /**
     * Scope untuk filter status aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk pencarian.
     */
    public function scopeCari($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('kode', 'like', "%{$keyword}%")
              ->orWhere('nama', 'like', "%{$keyword}%");
        });
    }
}
