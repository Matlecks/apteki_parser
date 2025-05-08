<?php

namespace App\Enums;

enum DutchCityEnum: string
{
    case AMSTERDAM = 'Amsterdam';
    case ROTTERDAM = 'Rotterdam';
    case THE_HAGUE = 'The Hague';
    case UTRECHT = 'Utrecht';
    case EINDHOVEN = 'Eindhoven';
    case GRONINGEN = 'Groningen';
    case ALMERE = 'Almere';
    case TILBURG = 'Tilburg';
    case BREDA = 'Breda';
    case NIJMEGEN = 'Nijmegen';
    case ENSCHEDE = 'Enschede';
    case HAARLEM = 'Haarlem';
    case ARNHEM = 'Arnhem';
    case ZAANDAM = 'Zaandam';
    case AMERSFOORT = 'Amersfoort';

    public static function casesToSelectArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
