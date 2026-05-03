<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'total',
        'bayar',
        'kembalian',
        'payment_method',
        'tanggal',
        'user_id',
        'shift_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Label metode pembayaran untuk tampilan.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'     => 'Cash',
            'transfer' => 'Transfer',
            'qris'     => 'QRIS',
            default    => ucfirst($this->payment_method),
        };
    }
}
