<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\GameEndedStatusReader;
use PHPUnit\Framework\TestCase;

class GameEndedStatusReaderTest extends TestCase
{
    public function testReportsAboutWinners(): void
    {
        $sut = new GameEndedStatusReader();

        $winner = $this->getMockForAbstractClass(DominoPlayer::class);
        $winner->method('getName')->willReturn('TestWinner');

        $game = $this->getMockForAbstractClass(DominoGame::class);
        $game->method('getStatus')->willReturn(GameStatusesEnum::ENDED);
        $game->method('getWinners')->willReturn([$winner]);

        $this->assertTrue($sut->supports($game));
        $this->assertStringContainsStringIgnoringCase('TestWinner', $sut->read($game));
    }

    public function testReportsAboutGameEndedWithoutWinners(): void
    {
        $sut = new GameEndedStatusReader();

        $game = $this->getMockForAbstractClass(DominoGame::class);
        $game->method('getStatus')->willReturn(GameStatusesEnum::ENDED);
        $game->method('getWinners')->willReturn([]);

        $this->assertTrue($sut->supports($game));
        $this->assertStringContainsStringIgnoringCase('No winners', $sut->read($game));
    }
}
