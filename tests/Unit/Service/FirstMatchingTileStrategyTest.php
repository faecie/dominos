<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service;

use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Service\FirstMatchingTileStrategy;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class FirstMatchingTileStrategyTest extends TestCase
{
    /**
     * @dataProvider boardStockProvider
     */
    public function testPicksFirstMatchingTile(Board $board, TileStock $stock, Tile $expectedTile): void
    {
        $sut = new FirstMatchingTileStrategy();
        $this->assertSame($expectedTile, $sut->addTile($stock, $board));
    }

    public function boardStockProvider(): array
    {
        $leftmostTileOnBoard = new Tile(1, 2);
        $rightmostTileOnBoard = new Tile(3, 4);
        $leftMatchingTile = new Tile(1, 3);
        $rightMatchingTile = new Tile(2, 4);

        $board1 = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board1->method('getLeft')->willReturn($leftmostTileOnBoard);
        $stock1 = $this->getMockForAbstractClass(TileStock::class);
        $stock1->method('pickTile')->with($leftmostTileOnBoard->getLeftSide())->willReturn($leftMatchingTile);

        $board2 = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board2->method('getRight')->willReturn($rightmostTileOnBoard);
        $board2->method('getLeft')->willReturn($leftmostTileOnBoard);
        $stock2 = $this->getMockForAbstractClass(TileStock::class);
        $stock2->method('pickTile')->willReturnCallback(
            static function (int $value) use ($rightMatchingTile, $rightmostTileOnBoard) {
                return $value === $rightmostTileOnBoard->getRightSide() ? $rightMatchingTile : null;
            });

        return [
            'Should add to the left of the board if found a match' =>
                [$board1, $stock1, $leftMatchingTile],
            'Should add to the right of the board if found a match' =>
                [$board2, $stock2, $rightMatchingTile],
        ];

    }

    public function testItKnowsWhenItCanExtendBoard(): void
    {
        $board = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board->method('getLeft')->willReturn(new Tile(1, 2));
        $board->method('getRight')->willReturn(new Tile(4, 5));
        $stock = $this->getMockForAbstractClass(TileStock::class);
        $stock->method('hasTile')->willReturn(true);

        $sut = new FirstMatchingTileStrategy();
        $this->assertTrue($sut->canAddTile($stock, $board));
    }

    public function testEmptyStockCanNotExtendBoard(): void
    {
        $leftmostTileOnBoard = new Tile(1, 2);
        $rightmostTileOnBoard = new Tile(4, 3);

        $board = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board->method('getLeft')->willReturn($leftmostTileOnBoard);
        $board->method('getRight')->willReturn($rightmostTileOnBoard);

        $stock = $this->getMockForAbstractClass(TileStock::class);
        $stock->method('pickTile')->willReturn(null);

        $sut = new FirstMatchingTileStrategy();

        $this->assertFalse($sut->canAddTile($stock, $board));
        $this->assertNull($sut->addTile($stock, $board));
    }
}
