<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'isbn',
        'resume',
        'nombre_exemplaires',
        'nombre_disponibles',
        'editeur',
        'annee_publication',
        'image_couverture',
        'categorie_id',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'annee_publication' => 'integer',
        'nombre_exemplaires' => 'integer',
        'nombre_disponibles' => 'integer',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class, 'livre_auteur');
    }

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }

    /**
     * Vérifier si le livre est disponible à l'emprunt
     */
    public function estDisponible(): bool
    {
        return $this->disponible && $this->nombre_disponibles > 0;
    }

    /**
     * Emprunter un exemplaire du livre
     * Retourne true si succès, false sinon
     */
    public function emprunter(): bool
    {
        if (!$this->estDisponible()) {
            return false;
        }

        // Décrémenter le nombre d'exemplaires disponibles
        $this->decrement('nombre_disponibles');
        
        // Marquer comme indisponible si plus d'exemplaires
        if ($this->nombre_disponibles === 0) {
            $this->update(['disponible' => false]);
        }
        
        return true;
    }

    /**
     * Retourner un exemplaire du livre
     */
    public function retourner(): void
    {
        // Incrémenter le nombre d'exemplaires disponibles
        $this->increment('nombre_disponibles');
        
        // Marquer comme disponible s'il y a au moins un exemplaire
        if ($this->nombre_disponibles > 0 && !$this->disponible) {
            $this->update(['disponible' => true]);
        }
    }

    /**
     * Vérifier la cohérence des données
     */
    public function verifierCoherence(): void
    {
        // S'assurer que nombre_disponibles ne dépasse pas nombre_exemplaires
        if ($this->nombre_disponibles > $this->nombre_exemplaires) {
            $this->update(['nombre_disponibles' => $this->nombre_exemplaires]);
        }

        // S'assurer que nombre_disponibles n'est pas négatif
        if ($this->nombre_disponibles < 0) {
            $this->update(['nombre_disponibles' => 0]);
        }

        // Mettre à jour le statut disponible en fonction du nombre d'exemplaires
        $devraitEtreDisponible = $this->nombre_disponibles > 0;
        if ($this->disponible !== $devraitEtreDisponible) {
            $this->update(['disponible' => $devraitEtreDisponible]);
        }
    }

    /**
     * Compter les emprunts actifs (en cours + en retard)
     */
    public function getNombreEmpruntsActifsAttribute(): int
    {
        return $this->emprunts()
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->whereNull('date_retour_effective')
            ->count();
    }

    /**
     * Obtenir les noms des auteurs
     */
    public function getAuteursNomAttribute(): string
    {
        return $this->auteurs->pluck('nom')->implode(', ');
    }

    /**
     * Obtenir les noms complets des auteurs
     */
    public function getAuteursCompletAttribute(): string
    {
        return $this->auteurs->map(function($auteur) {
            return $auteur->full_name;
        })->implode(', ');
    }

    /**
     * Scope pour les livres disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true)
                     ->where('nombre_disponibles', '>', 0);
    }

    /**
     * Scope pour les livres indisponibles
     */
    public function scopeIndisponibles($query)
    {
        return $query->where('disponible', false)
                     ->orWhere('nombre_disponibles', '<=', 0);
    }
}