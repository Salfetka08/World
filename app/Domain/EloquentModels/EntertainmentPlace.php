<?php

namespace App\Domain\EloquentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class EntertainmentPlace extends Model
{
    protected $table = 'world_app.entertainment_places';

    protected $fillable = [
        'name', 'category', 'latitude', 'longitude', 'address',
        'details', 'city', 'country', 'phone', 'website',
        'working_hours', 'rating', 'price_level', 'is_active'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'details' => AsArrayObject::class,
        'working_hours' => AsArrayObject::class,
        'rating' => 'float',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Скоупы для удобной фильтрации
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
