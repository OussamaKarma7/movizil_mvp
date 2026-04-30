<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    protected $fillable = [
        'client_id',
        'parent_id',
        'type',
        'start_date',
        'end_date',
        'duration',
        'price',
        'cin_path',
        'certificat_path',
        'status',
        'is_renewal'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_renewal' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function original(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'parent_id');
    }

    public function renewals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class, 'parent_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
