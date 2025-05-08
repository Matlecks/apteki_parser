<?php

namespace App\Enums;

enum GermanCityEnum: string
{
    case BERLIN = 'Berlin';
    case HAMBURG = 'Hamburg';
    case MUNICH = 'Munich';
    case COLOGNE = 'Cologne';
    case FRANKFURT = 'Frankfurt';
    case STUTTGART = 'Stuttgart';
    case DUSSELDORF = 'Düsseldorf';
    case LEIPZIG = 'Leipzig';
    case DORTMUND = 'Dortmund';
    case ESSEN = 'Essen';
    case DRESDEN = 'Dresden';
    case BREMEN = 'Bremen';
    case HANOVER = 'Hanover';
    case NUREMBERG = 'Nuremberg';
    case DUISBURG = 'Duisburg';
    case BOCHUM = 'Bochum';

    public static function casesToSelectArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
