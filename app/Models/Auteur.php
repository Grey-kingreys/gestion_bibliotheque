<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'nationalite',
        'biographie',
    ];

    public function livres()
    {
        return $this->belongsToMany(Livre::class, 'livre_auteur');
    }

    public function getFullNameAttribute(): string
    {
        return $this->prenom ? "{$this->prenom} {$this->nom}" : $this->nom;
    }

    public function getNombreLivresAttribute(): int
    {
        return $this->livres()->count();
    }
}