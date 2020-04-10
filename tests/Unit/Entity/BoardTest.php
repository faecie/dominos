<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Entity;

use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Exception\TileMisplacementException;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public function testAddRight(): void
    {
        $tile = new Tile(3, 6);
        $sut = new Board(new Tile(2, 6));
        $sut->addRight($tile);

        $this->assertSame($tile, $sut->getRight());
    }

    public function testAddLeft(): void
    {
        $tile = new Tile(2, 5);
        $sut = new Board(new Tile(2, 6));
        $sut->addLeft($tile);

        $this->assertSame($tile, $sut->getLeft());
    }

    public function testAddingWrongTileToTheLeft(): void
    {
        $this->expectException(TileMisplacementException::class);

        $tile = new Tile(3, 5);
        $sut = new Board(new Tile(2, 6));
        $sut->addLeft($tile);
    }

    public function testAddingWrongTileToTheRight(): void
    {
        $this->expectException(TileMisplacementException::class);

        $tile = new Tile(3, 5);
        $sut = new Board(new Tile(2, 6));
        $sut->addRight($tile);
    }

    public function testStringRepresentationContainsAllTiles(): void
    {
        $sut = new Board(new Tile(2, 6));
        $sut->addRight(new Tile(3, 6));
        $sut->addLeft(new Tile(2, 5));

        $this->assertThat($sut->toString(), $this->matchesRegularExpression('/.*5.*2.*2.*6.*6.*3.*/'));
    }
}
