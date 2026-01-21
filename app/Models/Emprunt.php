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

    /**
     * Vérifier si l'emprunt est en retard
     */
    public function estEnRetard(): bool
    {
        // Si déjà retourné ou rejeté, pas de retard
        if (in_array($this->statut, ['retourne', 'rejete'])) {
            return false;
        }

        // Si pas encore validé, pas de retard
        if ($this->statut === 'en_attente') {
            return false;
        }

        // Vérifier si la date de retour prévue est dépassée
        return Carbon::now()->isAfter($this->date_retour_prevue);
    }

    /**
     * Calculer le nombre de jours de retard
     */
    public function joursDeRetard(): int
    {
        if (!$this->estEnRetard()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->date_retour_prevue);
    }

    /**
     * Calculer le montant de la pénalité (500 GNF par jour)
     */
    public function calculerPenalite(): float
    {
        $jours = $this->joursDeRetard();
        return $jours * 500;
    }

    /**
     * Marquer l'emprunt comme retourné
     */
    public function marquerCommeRetourne(): void
    {
        $etaitEnRetard = $this->estEnRetard();
        $joursRetard = $this->joursDeRetard();

        // IMPORTANT: Mettre à jour d'abord avant de créer la pénalité
        $this->update([
            'date_retour_effective' => Carbon::now(),
            'statut' => 'retourne',
        ]);

        // Créer une pénalité si retard
        if ($etaitEnRetard && $joursRetard > 0) {
            Penalite::create([
                'emprunt_id' => $this->id,
                'montant' => $this->calculerPenalite(),
                'jours_retard' => $joursRetard,
                'motif' => "Retard de {$joursRetard} jour(s) pour le retour du livre",
            ]);
        }

        // Rendre le livre disponible
        $this->livre->retourner();
    }

    /**
     * Valider l'emprunt (passage de en_attente à en_cours)
     */
    public function valider(): bool
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }

        // Vérifier la disponibilité du livre
        if (!$this->livre->estDisponible()) {
            return false;
        }

        // Emprunter le livre
        $this->livre->emprunter();

        // Mettre à jour l'emprunt
        $this->update([
            'statut' => 'en_cours',
            'date_emprunt' => Carbon::now(),
        ]);

        return true;
    }

    /**
     * Rejeter l'emprunt
     */
    public function rejeter(string $motif): void
    {
        $this->update([
            'statut' => 'rejete',
            'commentaire' => $motif,
        ]);
    }

    // ========== SCOPES ==========

    /**
     * Emprunts en cours (SANS les retards)
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours')
                     ->whereNull('date_retour_effective');
    }

    /**
     * Emprunts en retard UNIQUEMENT
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
                     ->whereNull('date_retour_effective');
    }

    /**
     * Emprunts retournés
     */
    public function scopeRetourne($query)
    {
        return $query->where('statut', 'retourne');
    }

    /**
     * Emprunts en attente de validation
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
    
    /**
     * Emprunts rejetés
     */
    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    /**
     * Emprunts actifs (en cours + en retard)
     */
    public function scopeActifs($query)
    {
        return $query->whereIn('statut', ['en_cours', 'en_retard'])
                     ->whereNull('date_retour_effective');
    }
}