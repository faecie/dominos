<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service\GameStatusReader;

use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\GameStatusReader\PlayerPutTileStatusReader;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class PlayerPutTileStatusReaderTest extends TestCase
{
    public function testCanTellAboutPlayerPutHisTileOnBoard(): void
    {
        $sut = new PlayerPutTileStatusReader();

        $playersTile = new Tile(1, 2);
        $boardAdjacentTile = new Tile(2, 3);

        $player = $this->getMockForAbstractClass(DominoPlayer::class);
        $player->method('getStatus')->willReturn(PlayerStatusesEnum::ADDED_TILE);
        $player->method('getName')->willReturn('TestPlayer');
        $player->method('getLastTile')->willReturn($playersTile);

        $board = $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock();
        $board->method('getAdjacentTile')->willReturn($boardAdjacentTile);

        $game = $this->getMockForAbstractClass(DominoGame::class);
        $game->method('getStatus')->willReturn(GameStatusesEnum::PLAYED);
        $game->method('getCurrentPlayer')->willReturn($player);
        $game->method('getBoard')->willReturn($board);

        $this->assertTrue($sut->supports($game));
        $this->assertStringContainsStringIgnoringCase('TestPlayer', $sut->read($game));
        $this->assertStringContainsStringIgnoringCase($boardAdjacentTile->toString(), $sut->read($game));
        $this->assertStringContainsStringIgnoringCase($playersTile->toString(), $sut->read($game));
    }
}
