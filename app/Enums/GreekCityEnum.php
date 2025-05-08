<?php

namespace App\Enums;

enum GreekCityEnum: string
{
    case ATHENS = 'Athens';
    case THESSALONIKI = 'Thessaloniki';
    case PATRAS = 'Patras';
    case HERAKLION = 'Heraklion';
    case LARISSA = 'Larissa';
    case VOLOS = 'Volos';
    case IOANNINA = 'Ioannina';
    case TRIKALA = 'Trikala';
    case CHANIA = 'Chania';
    case RHODES = 'Rhodes';
    case ALEXANDROUPOLI = 'Alexandroupoli';
    case KAVALA = 'Kavala';
    case KALAMATA = 'Kalamata';
    case VERIA = 'Veria';
    case SERRES = 'Serres';
    case XANTHI = 'Xanthi';
    case KATERINI = 'Katerini';
    case LAMIA = 'Lamia';
    case KOMOTINI = 'Komotini';
    case DRAMA = 'Drama';
    case NAXOS = 'Naxos';
    case CORFU = 'Corfu';
    case MYTILENE = 'Mytilene';
    case CHIOS = 'Chios';
    case ZAKYNTHOS = 'Zakynthos';

    public static function casesToSelectArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
