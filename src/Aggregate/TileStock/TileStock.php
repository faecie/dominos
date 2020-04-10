<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

use Countable;
use Faecie\Dominos\ValueObject\Tile;

interface TileStock extends Countable
{
    /**
     * Tells whether there is a tile in the stock
     *
     * @param int|null $value Searches for a tile with specified value if given
     *
     * @return bool
     */
    public function hasTile(int $value = null): bool;

    /**
     * Picks a tile with provided value if given. Picked tile is removed from a stock
     *
     * @param int|null $value The value to be searched for
     *
     * @return Tile
     */
    public function pickTile(int $value = null): ?Tile;

    /**
     * Puts a tile in the stock
     *
     * @param Tile $tile The tile to put in the stock
     *
     * @return void
     */
    public function putTile(Tile $tile): void;
}
