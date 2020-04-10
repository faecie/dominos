<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\ValueObject;

use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    public function testCanReverseTile(): void
    {
        $sut = new Tile(6, 5);

        $this->assertSame([$sut->getRightSide(), $sut->getLeftSide()], [5, 6]);
        $sut->reverse();
        $this->assertSame([$sut->getRightSide(), $sut->getLeftSide()], [6, 5]);
    }

    public function testTileStringRepresentationContainsItsValues(): void
    {
        $sut = new Tile(6, 5);
        $this->assertStringContainsString('6', $sut->toString());
        $this->assertStringContainsString('5', $sut->toString());
    }
}
