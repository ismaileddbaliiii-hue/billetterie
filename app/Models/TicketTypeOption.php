<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTypeOption extends Model
{
    protected $fillable = [
        'name',
        'label',
        'icon',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
    ];

    // Scope for active types
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Scope for ordered types
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
