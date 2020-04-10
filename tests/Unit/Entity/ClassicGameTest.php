<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Entity;

use ArrayIterator;
use Faecie\Dominos\Aggregate\PlayersQueue;
use Faecie\Dominos\Aggregate\TileStock\TileStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\ClassicGame;
use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class ClassicGameTest extends TestCase
{
    public function testOnlyThoseWithWinnerStatusAreWinners(): void
    {
        $player1 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player1->method('getStatus')->willReturn(PlayerStatusesEnum::WINNER);
        $player2 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player2->method('getStatus')->willReturn(PlayerStatusesEnum::CANT_PLAY);

        $players = $this->getMockForAbstractClass(PlayersQueue::class);
        $players->method('getIterator')->willReturn(new ArrayIterator([$player1, $player2]));

        $sut = new ClassicGame(
            $players,
            $this->getMockForAbstractClass(TileStock::class),
            new Board(new Tile(1, 2)),
        );

        $winners = $sut->getWinners();
        $this->assertCount(1, $winners);
        $this->assertSame($player1, $winners[0]);
    }

    /**
     * @dataProvider gameEndedProvider
     */
    public function testGameEnded(PlayersQueue $players, bool $expectedAnswer): void
    {
        $sut = new ClassicGame(
            $players,
            $this->getMockForAbstractClass(TileStock::class),
            $this->getMockBuilder(Board::class)->disableOriginalConstructor()->getMock(),
        );

        $this->assertSame($expectedAnswer, $sut->hasEnded());
    }

    public function gameEndedProvider(): array
    {
        $winner1 = $this->getMockForAbstractClass(DominoPlayer::class);
        $winner1->method('getStatus')->willReturn(PlayerStatusesEnum::WINNER);
        $cantPlay1 = $this->getMockForAbstractClass(DominoPlayer::class);
        $cantPlay1->method('getStatus')->willReturn(PlayerStatusesEnum::CANT_PLAY);
        $cantPlay2 = $this->getMockForAbstractClass(DominoPlayer::class);
        $cantPlay2->method('getStatus')->willReturn(PlayerStatusesEnum::CANT_PLAY);
        $mayPlay2 = $this->getMockForAbstractClass(DominoPlayer::class);
        $mayPlay2->method('getStatus')->willReturn(PlayerStatusesEnum::ADDED_TILE);

        $oneWinnerAnotherCantPlay = $this->getMockForAbstractClass(PlayersQueue::class);
        $oneWinnerAnotherCantPlay->method('getIterator')->willReturn(new ArrayIterator([$winner1, $cantPlay2]));

        $canPlayButThereIsAWinner = $this->getMockForAbstractClass(PlayersQueue::class);
        $canPlayButThereIsAWinner->method('getIterator')->willReturn(new ArrayIterator([$winner1, $mayPlay2]));

        $cantPlay = $this->getMockForAbstractClass(PlayersQueue::class);
        $cantPlay->method('getIterator')->willReturn(new ArrayIterator([$cantPlay1, $cantPlay2]));

        $NoWinnersButOneCanPlay = $this->getMockForAbstractClass(PlayersQueue::class);
        $NoWinnersButOneCanPlay->method('getIterator')->willReturn(new ArrayIterator([$cantPlay1, $mayPlay2]));

        return [
            'When there is no winners and nobody can play the game should be ended' =>
                [$cantPlay, true],
            'When there is no winners and somebody can play then the game should not be ended' =>
                [$NoWinnersButOneCanPlay, false],
            'When players can play but there is a winner then the game should be ended' =>
                [$canPlayButThereIsAWinner, true],
            'When nobody can play and there is a winner the game should be ended' =>
                [$oneWinnerAnotherCantPlay, true],
        ];
    }
}
