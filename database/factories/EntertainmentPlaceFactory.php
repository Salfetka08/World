<?php
// database/factories/EntertainmentPlaceFactory.php

namespace Database\Factories;

use App\Domain\EloquentModels\EntertainmentPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntertainmentPlaceFactory extends Factory
{
    protected $model = EntertainmentPlace::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'category' => $this->faker->randomElement(['museum', 'cinema', 'park', 'restaurant', 'cafe']),
            'latitude' => $this->faker->latitude(55.5, 60.0),
            'longitude' => $this->faker->longitude(30.0, 55.0),
            'address' => $this->faker->address,
            'city' => $this->faker->randomElement(['Москва', 'Санкт-Петербург', 'Казань', 'Новосибирск']),
            'country' => 'Россия',
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'price_level' => $this->faker->numberBetween(1, 4),
            'details' => json_encode([
                'description' => $this->faker->sentence,
            ]),
            'working_hours' => json_encode([
                'weekdays' => '09:00-21:00',
                'weekend' => '10:00-20:00'
            ]),
            'is_active' => true,
        ];
    }
}
