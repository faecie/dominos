<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

use Faecie\Dominos\ValueObject\Tile;
use SplSubject;

interface DominoGame extends SplSubject, WithStatus
{
    public function hasExtraTile(): bool;

    public function getExtraTile(): ?Tile;

    public function getBoard(): Board;

    public function play(): void;

    public function getCurrentPlayer(): ?DominoPlayer;

    public function getWinners(): array;

    public function hasEnded(): bool;
}
