<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\ValueObject\Tile;
use SplSubject;

interface DominoPlayer extends SplSubject, WithStatus
{
    /**
     * Returns the name of a player
     */
    public function getName(): string;

    /**
     * Get last used tile
     */
    public function getLastTile(): ?Tile;

    /**
     * Play a round in a game
     *
     * @param DominoGame $game The game to play
     *
     * @return int resulting status {@see PlayerStatusesEnum}
     */
    public function play(DominoGame $game): int;
}
