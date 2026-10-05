<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Setting;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'nom',
    'prenom',
    'email',
    'password',
    'role',
    'telephone',
    'bio',
    'profile_image',
    'active',
    'perm_creation_event',
    'perm_modification_event',
    'perm_suppression_event',
    'perm_view_stats',
    'date_creation',
    'date_connexion',
];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
        'perm_creation_event' => 'boolean',
        'perm_modification_event' => 'boolean',
        'perm_suppression_event' => 'boolean',
        'perm_view_stats' => 'boolean',
        'date_creation' => 'datetime',
        'date_connexion' => 'datetime',
        'profile_image' => 'string',
    ];
  public function billets()
    {
        return $this->hasManyThrough(Billet::class, Commande::class, 'client_id', 'commande_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isOrganisateur(): bool
    {
        return $this->role === 'Organisateur';
    }

    public function isClient(): bool
    {
        return $this->role === 'Client';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'Admin') {
            return true;
        }
        return $this->$permission === true;
    }

    public function getProfileImageUrlAttribute(): ?string
    {
        $path = $this->profile_image;

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::url($path);
        }

        $defaultPath = Setting::get('profile_image');

        if ($defaultPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($defaultPath)) {
            return \Illuminate\Support\Facades\Storage::url($defaultPath);
        }

        return null;
    }

    public function getProfileImagePathAttribute(): ?string
    {
        if ($this->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_image)) {
            return $this->profile_image;
        }

        $defaultPath = Setting::get('profile_image');

        if ($defaultPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($defaultPath)) {
            return $defaultPath;
        }

        return null;
    }

    // Facilite l'utilisation de la logique utilisateur standard Jetstream (name)
    public function getNameAttribute(): string
    {
        return trim(($this->prenom ?? '') . ' ' . ($this->nom ?? ''));
    }

    public function setNameAttribute(string $value): void
    {
        $parts = preg_split('/\s+/', trim($value));

        $this->attributes['prenom'] = $parts[0] ?? '';
        $this->attributes['nom'] = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'user_id');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'client_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}