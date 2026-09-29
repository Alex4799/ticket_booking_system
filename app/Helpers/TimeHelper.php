<?php

namespace App\Helpers;

class TimeHelper
{
    public static function convertMinutesToHoursMinutes($totalMinutes)
    {
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        return "{$hours}h {$minutes}min";
    }
}
