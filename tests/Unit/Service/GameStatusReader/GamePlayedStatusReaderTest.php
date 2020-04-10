<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\GamePlayedStatusReader;
use PHPUnit\Framework\TestCase;

class GamePlayedStatusReaderTest extends TestCase
{
    public function testReportsShowsTheBoard(): void
    {
        $sut = new GamePlayedStatusReader();

        $board = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board->method('toString')->willReturn('Test board output');


        $game = $this->getMockForAbstractClass(DominoGame::class);
        $game->method('getStatus')->willReturn(GameStatusesEnum::PLAYED);
        $game->method('getBoard')->willReturn($board);

        $this->assertTrue($sut->supports($game));
        $this->assertStringContainsStringIgnoringCase('Test board output', $sut->read($game));
    }
}
