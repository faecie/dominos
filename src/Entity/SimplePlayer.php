<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\ObserverSubjectTrait;
use Faecie\Dominos\Service\PlayStrategy;
use Faecie\Dominos\ValueObject\Tile;

/**
 * This is a simple player. She has no specific tactics to play. She takes a tile when she can do it,
 * draws a tile when she can't or skips the round.
 */
class SimplePlayer implements DominoPlayer
{
    use ObserverSubjectTrait;

    private string $name;
    private TileStock $stock;
    private PlayStrategy $strategy;
    private int $status;
    private ?Tile $lastTile;

    public function __construct(string $name, TileStock $stock, PlayStrategy $strategy)
    {
        $this->name = $name;
        $this->stock = $stock;
        $this->strategy = $strategy;
        $this->status = PlayerStatusesEnum::IDLE;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getLastTile(): ?Tile
    {
        return $this->lastTile;
    }

    public function play(DominoGame $game): int
    {
        if ($this->stock->count() === 0) {
            $this->status = PlayerStatusesEnum::WINNER;

            return $this->getStatus();
        }

        return $this->doPlay($game);
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function doPlay(DominoGame $game): int
    {
        $board = $game->getBoard();

        while (true) {
            if ($this->strategy->canAddTile($this->stock, $board)) {
                $this->lastTile = $this->strategy->addTile($this->stock, $board);
                $this->status = PlayerStatusesEnum::ADDED_TILE;
                $this->notify();

                return $this->getStatus();
            }

            $this->lastTile = $game->getExtraTile();
            if (!$this->lastTile instanceof Tile) {
                $this->status = PlayerStatusesEnum::CANT_PLAY;
                $this->notify();

                return $this->getStatus();
            }

            $this->stock->putTile($this->lastTile);
            $this->status = PlayerStatusesEnum::EXTRA_TILE;
            $this->notify();
        }

        return $this->getStatus();
    }
}
