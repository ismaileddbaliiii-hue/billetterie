<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'montant',
        'date',
        'prestataire',
        'statut',
        'commande_id',
        'transaction_id',
        'date_paiement',
        'source_token',
    ];

    protected $casts = [
        'date' => 'datetime',
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function isPaye(): bool
    {
        return $this->statut === 'Payé';
    }

    public function isEnAttente(): bool
    {
        return $this->statut === 'En attente';
    }

    public function isEchec(): bool
    {
        return $this->statut === 'Échec';
    }

    public function isRembourse(): bool
    {
        return $this->statut === 'Remboursé';
    }
}