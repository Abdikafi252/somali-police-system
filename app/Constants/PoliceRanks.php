<?php

namespace App\Constants;

class PoliceRanks
{
    public const RANKS = [
        'Dable' => 'Dable',
        'Alifle' => 'Alifle',
        'Laba Alifle' => 'Laba Alifle',
        'Saddex Alifle' => 'Saddex Alifle',
        'Xarigle' => 'Xarigle',
        'Laba Xarigle' => 'Laba Xarigle',
        'Xidigle' => 'Xidigle',
        'Laba Xidigle' => 'Laba Xidigle',
        'Saddex Xidigle' => 'Saddex Xidigle',
        'Gaashaanle' => 'Gaashaanle',
        'Gaashaanle Dhexe' => 'Gaashaanle Dhexe',
        'Gaashaanle Sare' => 'Gaashaanle Sare',
        'Gaas' => 'Gaas',
        'Sarreeye Guuto' => 'Sarreeye Guuto',
        'Sarreeye Gaas' => 'Sarreeye Gaas',
        'Sarreeye Guud' => 'Sarreeye Guud',
    ];

    public static function all(): array
    {
        return self::RANKS;
    }
}
