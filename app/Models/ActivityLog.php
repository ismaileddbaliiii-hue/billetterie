<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    
    protected $fillable = [
        'user_id',
        'action',
        'table_cible',
        'record_id',
        'timestamp',
        'ip',
        'niveau',
        'details'
    ];
    
    protected $casts = [
        'timestamp' => 'datetime',
    ];
    
    public $timestamps = false;

    public function getCreatedAtAttribute()
    {
        return $this->timestamp;
    }

    public function getUpdatedAtAttribute()
    {
        return $this->timestamp;
    }
    
    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Scope pour les logs critiques
     */
    public function scopeCritical($query)
    {
        return $query->where('niveau', 'CRITICAL');
    }
    
    /**
     * Scope pour les erreurs
     */
    public function scopeError($query)
    {
        return $query->where('niveau', 'ERROR');
    }
    
    /**
     * Scope pour les avertissements
     */
    public function scopeWarning($query)
    {
        return $query->where('niveau', 'WARNING');
    }
    
    /**
     * Scope pour les informations
     */
    public function scopeInfo($query)
    {
        return $query->where('niveau', 'INFO');
    }
}