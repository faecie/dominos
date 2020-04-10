<?php

declare(strict_types=1);

namespace Faecie\Dominos\Entity;

use Faecie\Dominos\Exception\TileMisplacementException;
use Faecie\Dominos\ValueObject\Tile;
use SplQueue;

class Board
{
    private SplQueue $tiles;
    private Tile $adjacentTile;

    public function __construct(Tile $tile)
    {
        $this->adjacentTile = $tile;
        $this->tiles = new SplQueue();
        $this->tiles->push($this->getAdjacentTile());
    }

    public function getLeft(): Tile
    {
        return $this->tiles->bottom();
    }

    public function getRight(): Tile
    {
        return $this->tiles->top();
    }

    public function getAdjacentTile(): Tile
    {
        return $this->adjacentTile;
    }

    public function addLeft(Tile $tile): void
    {
        $this->adjacentTile = $this->tiles->bottom();
        $adjacentValue = $this->getAdjacentTile()->getLeftSide();
        if (!in_array($adjacentValue, [$tile->getLeftSide(), $tile->getRightSide()], true)) {
            throw new TileMisplacementException($tile);
        }

        if ($tile->getLeftSide() === $adjacentValue) {
            $tile->reverse();
        }

        $this->tiles->unshift($tile);
    }

    public function addRight(Tile $tile): void
    {
        $this->adjacentTile = $this->tiles->top();
        $adjacentValue = $this->getAdjacentTile()->getRightSide();
        if (!in_array($adjacentValue, [$tile->getLeftSide(), $tile->getRightSide()], true)) {
            throw new TileMisplacementException($tile);
        }

        if ($tile->getRightSide() === $adjacentValue) {
            $tile->reverse();
        }

        $this->tiles->push($tile);
    }

    public function toString(): string
    {
        $tiles = iterator_to_array($this->tiles, false);

        return implode(' ', $tilesAsText = array_map(fn(Tile $tile) => $tile->toString(), $tiles));
    }
}
