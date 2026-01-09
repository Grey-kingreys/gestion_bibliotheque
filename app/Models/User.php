<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Radmin';
    }

    public function isBibliothecaire(): bool
    {
        return $this->role === 'Rbibliothecaire';
    }

    public function isLecteur(): bool
    {
        return $this->role === 'Rlecteur';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}