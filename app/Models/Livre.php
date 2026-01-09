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

    public function estDisponible(): bool
    {
        return $this->disponible && $this->nombre_disponibles > 0;
    }

    public function emprunter(): bool
    {
        if ($this->nombre_disponibles > 0) {
            $this->decrement('nombre_disponibles');
            
            if ($this->nombre_disponibles === 0) {
                $this->update(['disponible' => false]);
            }
            
            return true;
        }
        
        return false;
    }

    public function retourner(): void
    {
        $this->increment('nombre_disponibles');
        
        if (!$this->disponible) {
            $this->update(['disponible' => true]);
        }
    }

    public function getAuteursNomAttribute(): string
    {
        return $this->auteurs->pluck('nom')->implode(', ');
    }
}