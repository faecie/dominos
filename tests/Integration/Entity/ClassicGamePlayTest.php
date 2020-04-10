<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Integration\Entity;

use Faecie\Dominos\Aggregate\SimpleQueue;
use Faecie\Dominos\Aggregate\TileStock\ClassicDominoPlayerStock;
use Faecie\Dominos\Aggregate\TileStock\ClassicDominoStock;
use Faecie\Dominos\Aggregate\TileStock\RandomizedStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\ClassicGame;
use Faecie\Dominos\Entity\SimplePlayer;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Enum\PlayerStatusesEnum;
use Faecie\Dominos\Service\FirstMatchingTileStrategy;
use Faecie\Dominos\Service\GameLogger;
use Faecie\Dominos\Service\GameStatusReader\GameEndedStatusReader;
use Faecie\Dominos\ValueObject\Tile;
use PHPUnit\Framework\TestCase;

class ClassicGamePlayTest extends TestCase
{
    public function testOriginalSizes(): void
    {
        $gameStock = new ClassicDominoStock(new RandomizedStock());
        $this->assertSame($gameStock->count(), 28);

        $playersStock = new ClassicDominoPlayerStock($gameStock, new RandomizedStock());

        $this->assertSame($playersStock->count(), 7);
        $this->assertSame($gameStock->count(), 21);
    }

    public function testOriginalStockTilesPurifiedCreatingClassicOne(): void
    {
        $playersOriginalStock = new RandomizedStock();
        $playersOriginalStock->putTile(new Tile(24, 34));
        $playersOriginalStock->putTile(new Tile(25, 35));
        $gameStock = new ClassicDominoStock(new RandomizedStock());

        $playersStock = new ClassicDominoPlayerStock($gameStock, $playersOriginalStock);

        $this->assertFalse($playersStock->hasTile(35));
        $this->assertFalse($playersStock->hasTile(25));
        $this->assertFalse($playersStock->hasTile(24));
        $this->assertFalse($playersStock->hasTile(34));
    }

    public function testGameEndsWithNoWinners(): void
    {
        $stock1 = new RandomizedStock();
        $stock1->putTile(new Tile(1, 2));
        $stock2 = new RandomizedStock();
        $stock2->putTile(new Tile(3, 4));
        $player1 = new SimplePlayer('Test1', $stock1, new FirstMatchingTileStrategy());
        $player2 = new SimplePlayer('Test2', $stock2, new FirstMatchingTileStrategy());

        $game = new ClassicGame(
            new SimpleQueue([$player1, $player2]),
            new RandomizedStock(),
            new Board(new Tile(5, 6))
        );

        $gameLogger = new GameLogger([new GameEndedStatusReader()]);
        $game->attach($gameLogger);

        while ($game->getStatus() !== GameStatusesEnum::ENDED) {
            $game->play();
        }

        $this->assertTrue($game->hasEnded());
        $this->assertEmpty($game->getWinners());
    }

    public function testGameEndsWithWinner(): void
    {
        $stock1 = new RandomizedStock();
        $stock1->putTile(new Tile(1, 2));
        $stock2 = new RandomizedStock();
        $stock2->putTile(new Tile(3, 4));
        $player1 = new SimplePlayer('Test1', $stock1, new FirstMatchingTileStrategy());
        $player2 = new SimplePlayer('Test2', $stock2, new FirstMatchingTileStrategy());

        $game = new ClassicGame(
            new SimpleQueue([$player1, $player2]),
            new RandomizedStock(),
            new Board(new Tile(4, 5))
        );

        while ($game->getStatus() !== GameStatusesEnum::ENDED) {
            $game->play();
        }

        $this->assertTrue($game->hasEnded());
        $this->assertCount(1, $game->getWinners());
        $winner = $game->getWinners()[0];
        $this->assertSame('Test2', $winner->getName());
    }

    public function testRightOutcomeOfGame(): void
    {
        $gameStock = new RandomizedStock();
        $gameStock->putTile(new Tile(2, 6));

        $stock1 = new RandomizedStock();
        $stock1->putTile(new Tile(4, 2));
        $stock1->putTile(new Tile(6, 6));
        $stock2 = new RandomizedStock();
        $stock2->putTile(new Tile(3, 5));
        $stock2->putTile(new Tile(1, 1));

        $player1 = new SimplePlayer('Test1', $stock1, new FirstMatchingTileStrategy());
        $player2 = new SimplePlayer('Test2', $stock2, new FirstMatchingTileStrategy());

        $board = new Board(new Tile(4, 5));

        $game = new ClassicGame(new SimpleQueue([$player1, $player2]), $gameStock, $board);

        while ($game->getStatus() !== GameStatusesEnum::ENDED) {
            $game->play();
        }

        $winners = $game->getWinners();

        $this->assertCount(1, $winners, 'There should be only 1 winner');

        $this->assertSame(
            'Test1',
            $winners[array_key_first($winners)]->getName(),
            'Winners name should be Test1'
        );

        $this->assertThat(
            $board->getAdjacentTile()->toString(),
            $this->matchesRegularExpression('/.*6.*2.*/'),
            'The last tile before the final round should be <6:2>'
        );

        $this->assertThat(
            $board->toString(),
            $this->matchesRegularExpression('/.*6.*6.*6.*2.*2.*4.*4.*5.*5.*3.*/'),
            'Board should finally be <6:6> <6:2> <2:4> <4:5> <5:3>'
        );

        $this->assertSame(
            PlayerStatusesEnum::WINNER,
            $player1->getStatus(),
            'The first player should be a winner'
        );

        $this->assertSame(
            PlayerStatusesEnum::CANT_PLAY,
            $player2->getStatus(),
            'Second player should end with no tiles to add to the table',
        );
    }
}
