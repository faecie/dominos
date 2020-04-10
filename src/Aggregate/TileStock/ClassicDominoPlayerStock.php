<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

/**
 * This is a classic domino players stock. Player is given 7 tiles out of the main game stock.
 *
 * @link https://en.wikipedia.org/wiki/Dominoes
 */
class ClassicDominoPlayerStock implements TileStock
{
    use InnerStockDecoratingTrait;
    use StockPurifyingTrait;

    private const INITIAL_NUMBER_OF_TILES = 7;

    public function __construct(TileStock $gameStock, TileStock $innerStock)
    {
        $this->innerStock = $this->emptyStock($innerStock);
        while ($gameStock->count() > 0 && $innerStock->count() < self::INITIAL_NUMBER_OF_TILES) {
            $this->innerStock->putTile($gameStock->pickTile());
        }
    }
}
