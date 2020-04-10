<?php

declare(strict_types=1);

namespace Faecie\Dominos\Enum;

class PlayerStatusesEnum
{
    public const WINNER = 1;
    public const CANT_PLAY = 2;
    public const EXTRA_TILE = 3;
    public const ADDED_TILE = 4;
    public const IDLE = 5;
}
