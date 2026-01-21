<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Emprunt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'livre_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut',
        'commentaire',
    ];

    protected $casts = [
        'date_emprunt' => 'date',
        'date_retour_prevue' => 'date',
        'date_retour_effective' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function penalite()
    {
        return $this->hasOne(Penalite::class);
    }

    public function estEnRetard(): bool
    {
        if ($this->date_retour_effective) {
            return false;
        }

        return Carbon::now()->isAfter($this->date_retour_prevue);
    }

    public function joursDeRetard(): int
    {
        if (!$this->estEnRetard()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->date_retour_prevue);
    }

    public function calculerPenalite(): float
    {
        $jours = $this->joursDeRetard();
        return $jours * 500;
    }

    public function marquerCommeRetourne(): void
    {
        $etaitEnRetard = $this->estEnRetard();

        $this->update([
            'date_retour_effective' => Carbon::now(),
            'statut' => 'retourne',
        ]);

        if ($etaitEnRetard) {
            Penalite::create([
                'emprunt_id' => $this->id,
                'montant' => $this->calculerPenalite(),
                'jours_retard' => $this->joursDeRetard(),
                'motif' => 'Retard de retour de livre',
            ]);
        }

        $this->livre->retourner();
    }


    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
                     ->orWhere(function($q) {
                         $q->where('statut', 'en_cours')
                           ->whereDate('date_retour_prevue', '<', Carbon::now());
                     });
    }

    public function scopeRetourne($query)
    {
        return $query->where('statut', 'retourne');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
    
    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }
}
