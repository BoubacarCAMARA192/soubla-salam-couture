<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Helper d'affichage des dates en français.
 */
class Date
{
    public static function label($date, string $format = 'diffus'): string
    {
        if (!$date instanceof \DateTimeInterface) {
            try {
                $date = Carbon::parse($date);
            } catch (\Throwable) {
                return '';
            }
        }

        Carbon::setLocale('fr');
        $at = $date->format('H') == '00' && $date->format('i') == '00'
            ? ''
            : ' à '.str_replace(':', 'h', $date->format('H:i'));

        return $date->translatedFormat('j').' '
            .months()[$date->format('n') - 1].' '
            .$date->format('Y').$at;
    }

    public static function monthLabel(int $month, int $year): string
    {
        $months = months();

        return $months[$month - 1].' '.$year;
    }

    public static function dayLabel($date): string
    {
        if (!$date) {
            return '';
        }

        if (!$date instanceof \DateTimeInterface) {
            $date = Carbon::parse($date);
        }

        return daysLong()[$date->format('N') - 1];
    }
}

if (!function_exists('months')) {
    function months(): array
    {
        return ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    }
}

if (!function_exists('daysLong')) {
    function daysLong(): array
    {
        return ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    }
}
