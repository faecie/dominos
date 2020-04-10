<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Aggregate\TileStock;

use Faecie\Dominos\Aggregate\TileStock\ClassicDominoStock;
use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class ClassicDominoStockTest extends TestCase
{
    public function testStockContains28Tiles(): void
    {
        $actualTiles = [];
        $innerStock = $this->getMockForAbstractClass(TileStock::class);
        $innerStock->expects(self::once())->method('count')->willReturn(0);
        $innerStock->expects(self::exactly(28))
            ->method('putTile')->willReturnCallback(static function (Tile $tile) use (&$actualTiles) {
                $actualTiles[] = [
                    min($tile->getLeftSide(), $tile->getRightSide()),
                    max($tile->getLeftSide(), $tile->getRightSide()),
                ];
            });

        new ClassicDominoStock($innerStock);

        $this->assertEqualsCanonicalizing($this->getClassic28Tiles(), $actualTiles);
    }

    private function getClassic28Tiles(): array
    {
        return [
            [0, 0],
            [0, 1],
            [1, 1],
            [0, 2],
            [1, 2],
            [2, 2],
            [0, 3],
            [1, 3],
            [2, 3],
            [3, 3],
            [0, 4],
            [1, 4],
            [2, 4],
            [3, 4],
            [4, 4],
            [0, 5],
            [1, 5],
            [2, 5],
            [3, 5],
            [4, 5],
            [5, 5],
            [0, 6],
            [1, 6],
            [2, 6],
            [3, 6],
            [4, 6],
            [5, 6],
            [6, 6],
        ];
    }
}
