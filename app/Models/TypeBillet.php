<?php
// app/Models/TypeBillet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeBillet extends Model
{
    use HasFactory;

    protected $table = 'types_billets';

    protected $fillable = [
        'evenement_id',
        'nom',
        'description',
        'prix',
        'quantite',
        'quantite_vendue',
        'actif',
        'date_vente_debut',
        'date_vente_fin',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
        'quantite_vendue' => 'integer',
        'actif' => 'boolean',
        'date_vente_debut' => 'datetime',
        'date_vente_fin' => 'datetime',
    ];

    protected $appends = ['type'];

    /**
     * Get the type name (alias for nom field)
     */
    public function getTypeAttribute()
    {
        return $this->nom;
    }

    /**
     * Relation avec l'événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    /**
     * Relation avec les billets
     */
    public function billets()
    {
        return $this->hasMany(Billet::class);
    }

    /**
     * Calculer le nombre de places disponibles
     */
    public function getPlacesDisponiblesAttribute()
    {
        return max(0, $this->quantite - $this->quantite_vendue);
    }

    /**
     * Vérifier si le type de billet est épuisé
     */
    public function getEstEpuiseAttribute()
    {
        return $this->places_disponibles <= 0;
    }

    /**
     * Vérifier si la vente est active
     */
    public function getVenteActiveAttribute()
    {
        if (!$this->actif) {
            return false;
        }

        $now = now();
        
        if ($this->date_vente_debut && $now < $this->date_vente_debut) {
            return false;
        }
        
        if ($this->date_vente_fin && $now > $this->date_vente_fin) {
            return false;
        }
        
        return true;
    }
}