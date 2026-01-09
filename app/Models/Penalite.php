<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penalite extends Model
{
    use HasFactory;

    protected $fillable = [
        'emprunt_id',
        'montant',
        'jours_retard',
        'payee',
        'date_paiement',
        'motif',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'jours_retard' => 'integer',
        'payee' => 'boolean',
        'date_paiement' => 'date',
    ];

    public function emprunt()
    {
        return $this->belongsTo(Emprunt::class);
    }

    public function marquerCommePayee(): void
    {
        $this->update([
            'payee' => true,
            'date_paiement' => Carbon::now(),
        ]);
    }

    public function scopeNonPayees($query)
    {
        return $query->where('payee', false);
    }

    public function scopePayees($query)
    {
        return $query->where('payee', true);
    }

    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' GNF';
    }
}