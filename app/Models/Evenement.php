<?php
// app/Models/Evenement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'type',
        'date',
        'date_fin',
        'lieu_id',
        'capacite',
        'statut',
        'user_id',
        'conditions',
        'date_vente_debut',
        'date_vente_fin',
        'affiche',
        'banniere',
    ];

    protected $casts = [
        'date' => 'datetime',
        'date_fin' => 'datetime',
        'date_vente_debut' => 'datetime',
        'date_vente_fin' => 'datetime',
        'capacite' => 'integer',
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lieu()
    {
        return $this->belongsTo(Lieu::class);
    }

    public function billets()
    {
        return $this->hasMany(Billet::class);
    }

    // Ajouter cette relation
    public function typesBillets()
    {
        return $this->hasMany(TypeBillet::class);
    }

    public function isPublie(): bool
    {
        return $this->statut === 'Publié';
    }

    public function isTermine(): bool
    {
        return $this->statut === 'Terminé' || $this->date->isPast();
    }

    public function isPast(): bool
    {
        return $this->date && $this->date->isPast();
    }

    public function getDisplayStatus(): string
    {
        if ($this->isPast()) {
            return 'Annulé';
        }
        return $this->statut;
    }

    public function getBilletVendus(): int
    {
        return $this->billets()->where('statut', 'Valide')->count();
    }

    public function placesRestantes(): int
    {
        $placesVendues = $this->getBilletVendus();
        return max(0, $this->capacite - $placesVendues);
    }

    public function estComplet(): bool
    {
        return $this->placesRestantes() === 0;
    }
}