<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $fillable = [
        'client_id',
        'company_name',
        'ice',
        'rc',
        'rce',
        'if',
        'legal_form',
        'activity',
        'headquarters_address',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
