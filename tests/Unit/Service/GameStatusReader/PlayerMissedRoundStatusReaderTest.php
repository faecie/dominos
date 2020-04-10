<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\PlayerMissedRoundStatusReader;
use PHPUnit\Framework\TestCase;

class PlayerMissedRoundStatusReaderTest extends TestCase
{
    public function testSaysAboutPlayerMissedRound(): void
    {
        $sut = new PlayerMissedRoundStatusReader();

        $player = $this->getMockForAbstractClass(DominoPlayer::class);
        $player->method('getStatus')->willReturn(PlayerStatusesEnum::CANT_PLAY);
        $player->method('getName')->willReturn('TestPlayer');

        $this->assertTrue($sut->supports($player));
        $this->assertStringContainsStringIgnoringCase('TestPlayer', $sut->read($player));
        $this->assertStringContainsStringIgnoringCase('cant play', $sut->read($player));
    }
}
