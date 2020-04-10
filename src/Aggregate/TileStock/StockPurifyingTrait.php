<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

trait StockPurifyingTrait
{
    private function emptyStock(TileStock $stock): TileStock
    {
        while ($stock->count() > 0) {
            $tile = $stock->pickTile();
            unset($tile);
        }

        return $stock;
    }
}
