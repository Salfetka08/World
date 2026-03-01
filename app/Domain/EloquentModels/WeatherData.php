<?php

namespace App\Domain\EloquentModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    protected $table = 'weather_data';

    protected $fillable = [
        'user_id',
        'temperature',
        'feels_like',
        'condition',
        'humidity',
        'wind_speed',
        'pressure',
        'sunrise',
        'sunset',
        'day_time',
        'season'
    ];

    protected $casts = [
        'temperature' => 'float',
        'feels_like' => 'float',
        'humidity' => 'integer',
        'wind_speed' => 'float',
        'pressure' => 'integer',
        'sunrise' => 'string',
        'sunset' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public const CONDITIONS = ['CLEAR', 'CLOUDY', 'RAINY', 'SNOWY', 'STORMY'];
    public const DAY_TIMES = ['MORNING', 'AFTERNOON', 'EVENING', 'NIGHT'];
    public const SEASONS = ['SPRING', 'SUMMER', 'AUTUMN', 'WINTER'];
}
