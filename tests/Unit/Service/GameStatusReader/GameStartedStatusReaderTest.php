<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\GameStartedStatusReader;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class GameStartedStatusReaderTest extends TestCase
{
    public function testReportsShowsTheBoard(): void
    {
        $sut = new GameStartedStatusReader();

        $board = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board->method('getAdjacentTile')->willReturn(new Tile(1, 2));


        $game = $this->getMockForAbstractClass(DominoGame::class);
        $game->method('getStatus')->willReturn(GameStatusesEnum::STARTED);
        $game->method('getBoard')->willReturn($board);

        $this->assertTrue($sut->supports($game));
        $this->assertThat($sut->read($game), $this->matchesRegularExpression('/.*1.*2.*/'));
    }
}
