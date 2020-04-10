<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Aggregate\TileStock;

use Faecie\Dominos\Aggregate\TileStock\RandomizedStock;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class RandomizedStockTest extends TestCase
{
    private const CHECK_TIMES = 5;
    private const TILES_CHECK_GROUP = 100;

    public function testPicksTilesRandomly(): void
    {
        $sut = new RandomizedStock();
        $previousTiles = [];
        foreach (range(0, self::TILES_CHECK_GROUP) as $tileNumber) {
            $previousTiles[$tileNumber] = new Tile(1, $tileNumber);
        }

        foreach (range(0, self::CHECK_TIMES) as $checkTime) {
            foreach ($previousTiles as $tile) {
                $sut->putTile($tile);
            }

            $pickedTiles = [];
            while ($sut->count() > 0) {
                $pickedTiles[] = $sut->pickTile();
            }

            $this->assertEqualsCanonicalizing($previousTiles, $pickedTiles);
            $this->assertNotEquals($pickedTiles, $previousTiles);

            $previousTiles = $pickedTiles;
        }
    }

    public function testPicksTilesWithValueRandomly(): void
    {
        $tiles = [];
        foreach (range(0, self::TILES_CHECK_GROUP) as $tileNumber) {
            $tiles[$tileNumber] = new Tile(1, $tileNumber);
        }

        $previousTile = $tiles[0];

        foreach (range(0, self::CHECK_TIMES) as $checkTime) {
            $sut = new RandomizedStock();

            foreach ($tiles as $tile) {
                $sut->putTile($tile);
            }

            $pickedTile = $sut->pickTile(1);

            $this->assertNotEquals($pickedTile, $previousTile);

            $previousTile = $pickedTile;
        }
    }

    /**
     * @dataProvider tileProvider
     */
    public function testCanSeeWhetherItHasATile(int $needle, array $haystack, bool $expectedAnswer): void
    {
        $sut = new RandomizedStock();
        array_walk($haystack, fn(Tile $tile) => $sut->putTile($tile));

        $this->assertSame($expectedAnswer, $sut->hasTile($needle));
    }

    public function tileProvider(): array
    {
        $tiles = array_map(fn($pair) => new Tile(... $pair), [[1, 1], [1, 2], [1, 3], [1, 4], [1, 5], [1, 6]]);

        return [
            'There is no a tile with requested value in a stock' => [8, $tiles, false],
            'There is a tile with requested value in a stock' => [4, $tiles, true],
        ];
    }

    public function testEmptyStockReturnsNull(): void
    {
        $sut = new RandomizedStock();
        $this->assertNull($sut->pickTile());
        $this->assertNull($sut->pickTile(5));
    }
}
