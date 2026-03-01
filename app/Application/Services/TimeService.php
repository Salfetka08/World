<?php

namespace App\Application\Services;

use Carbon\Carbon;

class TimeService
{
    /**
     * Определить время суток (утро/день/вечер/ночь)
     */
    public function getDayTime(): string
    {
        $hour = (int) Carbon::now('UTC')->format('H');

        return match (true) {
            $hour >= 5 && $hour < 12 => 'MORNING',
            $hour >= 12 && $hour < 17 => 'AFTERNOON',
            $hour >= 17 && $hour < 22 => 'EVENING',
            default => 'NIGHT',
        };
    }

    /**
     * Определить сезон (зима/весна/лето/осень)
     */
    public function getSeason(): string
    {
        $month = (int) Carbon::now('UTC')->format('m');

        return match (true) {
            $month >= 3 && $month <= 5 => 'SPRING',
            $month >= 6 && $month <= 8 => 'SUMMER',
            $month >= 9 && $month <= 11 => 'AUTUMN',
            default => 'WINTER',
        };
    }
}
