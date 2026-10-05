<?php
// app/Models/Billet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Billet extends Model
{
    protected $fillable = [
        'code_unique',
        'qr_content',
        'type_billet_id',
        'commande_id',
        'evenement_id',
        'nom_spectateur',
        'email_spectateur',
        'prix',
        'statut',
        'date_validation',
        'valide_par',
        'siege',
        'notes'
    ];

    protected $casts = [
        'date_validation' => 'datetime',
        'prix' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($billet) {
            if (empty($billet->code_unique)) {
                $billet->code_unique = (string) Str::uuid();
            }
            // Générer automatiquement qr_content si vide
            if (empty($billet->qr_content)) {
                $billet->qr_content = $billet->code_unique;
            }
        });
    }

    public function typeBillet()
    {
        return $this->belongsTo(TypeBillet::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function getQrCodeAttribute()
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->generate($this->qr_content ?? $this->code_unique);
    }

    public function getCodeAttribute()
    {
        return $this->code_unique;
    }
}