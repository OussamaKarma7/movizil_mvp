<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    protected $fillable = [
        'client_id',
        'ref',
        'date_creation',
        'parent_id',
        'type',
        'start_date',
        'end_date',
        'duration',
        'price',
        'interlocuteur',
        'remarque',
        'montant_ht',
        'cin_path',
        'certificat_path',
        'status',
        'is_renewal'
    ];

    protected $casts = [
        'date_creation' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_renewal' => 'boolean',
    ];

    /**
     * DROIT CONS. (days)
     */
    public function getDroitConsommeAttribute()
    {
        if (!$this->start_date || !$this->end_date) return 0;
        return \Carbon\Carbon::parse($this->start_date)->diffInDays(\Carbon\Carbon::parse($this->end_date));
    }

    /**
     * REEL/J (days since expiry)
     */
    public function getReelConsommeJAttribute()
    {
        if (!$this->end_date) return 0;
        $today = \Carbon\Carbon::today();
        $endDate = \Carbon\Carbon::parse($this->end_date);
        if ($today <= $endDate) return 0;
        return $endDate->diffInDays($today);
    }

    /**
     * REEL/MOIS
     */
    public function getReelConsommeMoisAttribute()
    {
        return $this->reel_consomme_j / 30;
    }

    /**
     * VALEUR (HT)
     */
    public function getCalculatedValeurHtAttribute()
    {
        if ($this->montant_ht !== null && $this->montant_ht > 0) {
            return $this->montant_ht;
        }
        return $this->reel_consomme_mois * 165;
    }

    /**
     * SURPLUS/J
     */
    public function getSurplusJourAttribute()
    {
        return $this->reel_consomme_j - $this->droit_consomme;
    }

    /**
     * SURPLUS/MOIS
     */
    public function getSurplusMoisAttribute()
    {
        return $this->surplus_jour / 30;
    }

    /**
     * ECHUES
     */
    public function getEchueStatusAttribute()
    {
        if (!$this->end_date) return 'Non échue';
        return now()->startOfDay() >= $this->end_date ? 'Échue' : 'Non échue';
    }


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
