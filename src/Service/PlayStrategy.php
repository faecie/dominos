<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service;

use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\ValueObject\Tile;

interface PlayStrategy
{
    /**
     * Takes a tile from the stock and puts it on the board if possible
     *
     * @param TileStock $stock Available domino tiles to make move with
     * @param Board $board Current playing domino board
     *
     * @return Tile|null returns added tile
     */
    public function addTile(TileStock $stock, Board $board): ?Tile;

    /**
     * Tells whether it's possible to add a tile on the board using this strategy
     *
     * @param TileStock $stock Available domino tiles to make move with
     * @param Board $board Current playing domino board
     *
     * @return bool Whether or not it is possible to add a tile
     */
    public function canAddTile(TileStock $stock, Board $board): bool;


}
