<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'cin',
        'phone',
        'email',
        'address',
        'sage_custom_id',
        'registration_cin_path',
        'registration_company_doc_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Contract::class);
    }
}
