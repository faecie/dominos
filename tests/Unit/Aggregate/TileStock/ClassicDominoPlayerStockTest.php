<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Aggregate\TileStock;

use Faecie\Dominos\Aggregate\TileStock\ClassicDominoPlayerStock;
use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class ClassicDominoPlayerStockTest extends TestCase
{
    public function testStockPull7TilesOutFromGameStock(): void
    {
        $innerStock = $this->getMockForAbstractClass(TileStock::class);
        $gameStock = $this->getMockForAbstractClass(TileStock::class);

        $tiles = [[1, 2], [1, 3], [1, 4], [1, 5], [1, 6], [2, 1], [3, 1]];
        $originalTiles = array_map(fn(array $pair) => new Tile($pair[0], $pair[1]), $tiles);
        $gameStockTiles = $originalTiles;
        $innerStockTiles = [];

        $gameStock->method('count')->willReturnCallback(
            static function () use (&$gameStockTiles) {
                return count($gameStockTiles);
            }
        );
        $gameStock->expects(self::exactly(7))->method('pickTile')->willReturnCallback(
            static function () use (&$gameStockTiles) {
                return array_pop($gameStockTiles);
            }
        );

        $innerStock->method('count')->willReturnCallback(
            static function () use (&$innerStockTiles) {
                return count($innerStockTiles);
            }
        );
        $innerStock->expects(self::exactly(7))->method('putTile')->willReturnCallback(
            static function (Tile $tile) use (&$innerStockTiles) {
                $innerStockTiles[] = $tile;
            }
        );

        new ClassicDominoPlayerStock($gameStock, $innerStock);

        $this->assertEqualsCanonicalizing($originalTiles, $innerStockTiles);
    }
}
