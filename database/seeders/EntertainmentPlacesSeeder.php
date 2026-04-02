<?php
// database/seeders/EntertainmentPlacesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\EloquentModels\EntertainmentPlace;

class EntertainmentPlacesSeeder extends Seeder
{
    public function run()
    {
        $places = [
            // Москва
            [
                'name' => 'Третьяковская галерея',
                'category' => 'museum',
                'latitude' => 55.741,
                'longitude' => 37.620,
                'address' => 'Лаврушинский пер., 10, Москва',
                'city' => 'Москва',
                'country' => 'Россия',
                'phone' => '+7 (495) 123-45-67',
                'website' => 'https://tretyakov.ru',
                'rating' => 4.8,
                'price_level' => 2,
                'details' => json_encode([
                    'description' => 'Главный музей русского искусства',
                    'ticket_price' => 500
                ]),
                'working_hours' => json_encode([
                    'monday' => ['10:00-18:00'],
                    'tuesday' => ['10:00-18:00'],
                    'wednesday' => ['10:00-18:00'],
                    'thursday' => ['10:00-21:00'],
                    'friday' => ['10:00-21:00'],
                    'saturday' => ['10:00-20:00'],
                    'sunday' => ['10:00-20:00']
                ])
            ],
            [
                'name' => 'Киноцентр "Октябрь"',
                'category' => 'cinema',
                'latitude' => 55.761,
                'longitude' => 37.613,
                'address' => 'ул. Новый Арбат, 24, Москва',
                'city' => 'Москва',
                'country' => 'Россия',
                'phone' => '+7 (495) 123-45-68',
                'website' => 'https://kinokentr.ru',
                'rating' => 4.5,
                'price_level' => 3,
                'details' => json_encode([
                    'description' => 'Современный киноцентр с 12 залами',
                    'features' => ['IMAX', '3D', 'кафе']
                ]),
                'working_hours' => json_encode([
                    'monday' => ['10:00-02:00'],
                    'tuesday' => ['10:00-02:00'],
                    'wednesday' => ['10:00-02:00'],
                    'thursday' => ['10:00-02:00'],
                    'friday' => ['10:00-03:00'],
                    'saturday' => ['10:00-03:00'],
                    'sunday' => ['10:00-02:00']
                ])
            ],
            [
                'name' => 'Центральный парк им. Горького',
                'category' => 'park',
                'latitude' => 55.733,
                'longitude' => 37.605,
                'address' => 'Крымский Вал, 9, Москва',
                'city' => 'Москва',
                'country' => 'Россия',
                'phone' => '+7 (495) 123-45-69',
                'website' => 'https://park-gorkogo.com',
                'rating' => 4.7,
                'price_level' => 1,
                'details' => json_encode([
                    'description' => 'Главный парк Москвы',
                    'features' => ['аттракционы', 'велодорожки', 'кафе']
                ]),
                'working_hours' => json_encode([
                    'monday' => ['00:00-00:00'],
                    'tuesday' => ['00:00-00:00'],
                    'wednesday' => ['00:00-00:00'],
                    'thursday' => ['00:00-00:00'],
                    'friday' => ['00:00-00:00'],
                    'saturday' => ['00:00-00:00'],
                    'sunday' => ['00:00-00:00']
                ])
            ],
            [
                'name' => 'Ресторан "Пушкинъ"',
                'category' => 'restaurant',
                'latitude' => 55.764,
                'longitude' => 37.607,
                'address' => 'Тверской бульвар, 26А, Москва',
                'city' => 'Москва',
                'country' => 'Россия',
                'phone' => '+7 (495) 123-45-70',
                'website' => 'https://pushkin-restaurant.ru',
                'rating' => 4.9,
                'price_level' => 4,
                'details' => json_encode([
                    'description' => 'Легендарный ресторан русской кухни',
                    'cuisine' => 'русская'
                ]),
                'working_hours' => json_encode([
                    'monday' => ['12:00-00:00'],
                    'tuesday' => ['12:00-00:00'],
                    'wednesday' => ['12:00-00:00'],
                    'thursday' => ['12:00-00:00'],
                    'friday' => ['12:00-02:00'],
                    'saturday' => ['12:00-02:00'],
                    'sunday' => ['12:00-00:00']
                ])
            ],
            [
                'name' => 'Кофейня "Кофе Хауз"',
                'category' => 'cafe',
                'latitude' => 55.752,
                'longitude' => 37.610,
                'address' => 'ул. Тверская, 15, Москва',
                'city' => 'Москва',
                'country' => 'Россия',
                'phone' => '+7 (495) 123-45-71',
                'website' => 'https://coffeehouse.ru',
                'rating' => 4.3,
                'price_level' => 2,
                'details' => json_encode([
                    'description' => 'Популярная сеть кофеен',
                    'features' => ['wi-fi', 'выпечка']
                ]),
                'working_hours' => json_encode([
                    'monday' => ['08:00-23:00'],
                    'tuesday' => ['08:00-23:00'],
                    'wednesday' => ['08:00-23:00'],
                    'thursday' => ['08:00-23:00'],
                    'friday' => ['08:00-23:00'],
                    'saturday' => ['09:00-23:00'],
                    'sunday' => ['09:00-22:00']
                ])
            ],

            // Санкт-Петербург
            [
                'name' => 'Государственный Эрмитаж',
                'category' => 'museum',
                'latitude' => 59.941,
                'longitude' => 30.313,
                'address' => 'Дворцовая наб., 34, Санкт-Петербург',
                'city' => 'Санкт-Петербург',
                'country' => 'Россия',
                'phone' => '+7 (812) 123-45-67',
                'website' => 'https://hermitagemuseum.org',
                'rating' => 4.9,
                'price_level' => 2,
                'details' => json_encode([
                    'description' => 'Один из величайших художественных музеев мира',
                    'ticket_price' => 500
                ]),
                'working_hours' => json_encode([
                    'tuesday' => ['10:30-18:00'],
                    'wednesday' => ['10:30-18:00'],
                    'thursday' => ['10:30-18:00'],
                    'friday' => ['10:30-20:00'],
                    'saturday' => ['10:30-20:00'],
                    'sunday' => ['10:30-18:00']
                ])
            ],
            [
                'name' => 'Невский проспект',
                'category' => 'park',
                'latitude' => 59.935,
                'longitude' => 30.335,
                'address' => 'Невский пр., Санкт-Петербург',
                'city' => 'Санкт-Петербург',
                'country' => 'Россия',
                'rating' => 4.8,
                'price_level' => 3,
                'details' => json_encode([
                    'description' => 'Главная улица города',
                    'features' => ['магазины', 'рестораны', 'архитектура']
                ])
            ]
        ];

        foreach ($places as $place) {
            EntertainmentPlace::create($place);
        }

        $this->command->info('Entertainment places seeded successfully!');
    }
}
