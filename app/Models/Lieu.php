<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lieu extends Model
{
    use HasFactory;
    protected $table = 'lieux';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'capacite',
        'plan_de_salle',
    ];

    protected $casts = [
        'capacite' => 'integer',
    ];

    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }
}