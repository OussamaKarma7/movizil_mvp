<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingEntry extends Model
{
    protected $table = 'accounting';

    protected $fillable = [
        'invoice_id',
        'account_number',
        'third_party_account',
        'label',
        'debit',
        'credit',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
