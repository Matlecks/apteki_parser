<?php

namespace App\Enums;

enum AustrianCityEnum: string
{
    case VIENNA = 'Vienna';
    case GRAZ = 'Graz';
    case LINZ = 'Linz';
    case SALZBURG = 'Salzburg';
    case INNSBRUCK = 'Innsbruck';
    case KLAGENFURT = 'Klagenfurt';
    case VILLACH = 'Villach';
    case WIENER_NEUSTADT = 'Wiener Neustadt';
    case STEYR = 'Steyr';
    case DORNBIRN = 'Dornbirn';
    case FELDKIRCH = 'Feldkirch';
    case BREGENZ = 'Bregenz';
    case WOLFSBERG = 'Wolfsberg';
    case LEONDING = 'Leonding';
    case KUFSTEIN = 'Kufstein';

    public static function casesToSelectArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
