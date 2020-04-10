<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate\TileStock;

use Faecie\Dominos\ValueObject\Tile;
use SplQueue;

/**
 * This is a classic 28 domino tile stock
 *
 * @link https://en.wikipedia.org/wiki/Dominoes
 */
class ClassicDominoStock implements TileStock
{
    use InnerStockDecoratingTrait;
    use StockPurifyingTrait;

    private const MAX_TILE_NUMBER = 6;

    public function __construct(TileStock $innerStock)
    {
        $this->innerStock = $this->emptyStock($innerStock);
        foreach ($this->generateTiles() as $tile) {
            $this->innerStock->putTile($tile);
        }
    }

    private function generateTiles(): array
    {
        $result = [];
        $stack = new SplQueue();
        $stack->push([0, 0]);
        while (!$stack->isEmpty()) {
            [$firstSide, $secondSide] = $stack->pop();
            $result[] = new Tile($firstSide, $secondSide);

            if ($firstSide < self::MAX_TILE_NUMBER) {
                $stack->push([$firstSide + 1, $secondSide]);
            }

            if ($firstSide === $secondSide && $firstSide < self::MAX_TILE_NUMBER) {
                $stack->push([$firstSide + 1, $secondSide + 1]);
            }
        }

        return $result;
    }
}
