<?php

namespace App\Domain\EloquentModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLocation extends Model
{
    use HasFactory;

    protected $table = 'user_locations';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'city_name',
        'country',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
