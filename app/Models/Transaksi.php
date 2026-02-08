<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'tanggal',
        'total',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Relasi ke User (yang membuat transaksi).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke items (detail transaksi).
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransaksiItem::class);
    }

    /**
     * Generate kode transaksi otomatis.
     * Format: TRX-YYYYMMDD-XXXX
     */
    public static function generateKode(): string
    {
        $today = now()->format('Ymd');
        $prefix = "TRX-{$today}-";
        
        $lastTrx = self::where('kode_transaksi', 'like', "{$prefix}%")
            ->orderBy('kode_transaksi', 'desc')
            ->first();
        
        if ($lastTrx) {
            $lastNumber = (int) substr($lastTrx->kode_transaksi, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung ulang total dari items.
     */
    public function hitungTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }
}
