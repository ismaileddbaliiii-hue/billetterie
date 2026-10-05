<?php
// app/Models/Commande.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'client_id',
        'evenement_id',
        'date',
        'montant_total',
        'methode_paiement',
        'email_spectateur',
        'statut',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'montant_total' => 'decimal:2',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function billets()
    {
        return $this->hasMany(Billet::class, 'commande_id');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    // Méthodes utilitaires
    public function isConfirmee(): bool
    {
        return $this->statut === 'Confirmée';
    }

    public function isEnAttente(): bool
    {
        return $this->statut === 'En attente';
    }

    public function isAnnulee(): bool
    {
        return $this->statut === 'Annulée';
    }
    
    public function isRemboursee(): bool
    {
        return $this->statut === 'Remboursée';
    }
}