<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service;

use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\ValueObject\Tile;

/**
 * This strategy picks a first tile from the stock that matches with any ends on the table
 */
class FirstMatchingTileStrategy implements PlayStrategy
{
    public function addTile(TileStock $stock, Board $board): ?Tile
    {
        $tile = $stock->pickTile($board->getLeft()->getLeftSide());
        if ($tile instanceof Tile) {
            $board->addLeft($tile);

            return $tile;
        }

        $tile = $stock->pickTile($board->getRight()->getRightSide());
        if ($tile instanceof Tile) {
            $board->addRight($tile);

            return $tile;
        }

        return null;
    }

    public function canAddTile(TileStock $stock, Board $board): bool
    {
        foreach ([$board->getLeft()->getLeftSide(), $board->getRight()->getRightSide()] as $targetVal) {
            if ($stock->hasTile($targetVal)) {
                return true;
            }
        }

        return false;
    }
}
