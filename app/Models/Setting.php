<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    
    protected $casts = [
        'value' => 'json',
    ];
    
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function getUrl($key, $default = null)
    {
        $value = self::get($key, $default);

        if (!$value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // path already contains storage prefix
        if (Str::startsWith($value, ['storage/', '/storage/'])) {
            $relative = ltrim($value, '/');
            if (file_exists(public_path($relative))) {
                return asset($relative);
            }
        }

        // public disk storage
        if (Storage::disk('public')->exists($value)) {
            return Storage::url($value);
        }

        if (file_exists(public_path($value))) {
            return asset($value);
        }

        if (file_exists(public_path('storage/' . ltrim($value, '/')))) {
            return asset('storage/' . ltrim($value, '/'));
        }

        return null;
    }
    
    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}