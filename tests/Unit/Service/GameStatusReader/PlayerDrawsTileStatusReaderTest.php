<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\PlayerDrawsTileStatusReader;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class PlayerDrawsTileStatusReaderTest extends TestCase
{
    public function testShowsPlayerDrawExtraTile(): void
    {
        $sut = new PlayerDrawsTileStatusReader();

        $extraTile = new Tile(1, 2);
        $player = $this->getMockForAbstractClass(DominoPlayer::class);
        $player->method('getStatus')->willReturn(PlayerStatusesEnum::EXTRA_TILE);
        $player->method('getLastTile')->willReturn($extraTile);
        $player->method('getName')->willReturn('TestPlayer');

        $this->assertTrue($sut->supports($player));
        $this->assertStringContainsStringIgnoringCase($extraTile->toString(), $sut->read($player));
        $this->assertStringContainsStringIgnoringCase('TestPlayer', $sut->read($player));
    }
}
