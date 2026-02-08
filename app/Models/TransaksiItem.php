<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiItem extends Model
{
    protected $fillable = [
        'transaksi_id',
        'master_data_id',
        'nama_item',
        'jumlah',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke Transaksi (header).
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Relasi ke MasterData (barang/produk).
     */
    public function masterData(): BelongsTo
    {
        return $this->belongsTo(MasterData::class);
    }

    /**
     * Hitung subtotal otomatis sebelum save.
     */
    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->subtotal = $item->jumlah * $item->harga;
        });
    }
}
