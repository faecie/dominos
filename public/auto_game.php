<?php

declare(strict_types=1);

use Faecie\Dominos\Aggregate\SimpleQueue;
use Faecie\Dominos\Aggregate\TileStock\ClassicDominoPlayerStock;
use Faecie\Dominos\Aggregate\TileStock\ClassicDominoStock;
use Faecie\Dominos\Aggregate\TileStock\RandomizedStock;
use Faecie\Dominos\Entity\Board;
use Faecie\Dominos\Entity\ClassicGame;
use Faecie\Dominos\Entity\SimplePlayer;
use Faecie\Dominos\Enum\GameStatusesEnum;
use Faecie\Dominos\Service\FirstMatchingTileStrategy;
use Faecie\Dominos\Service\GameLogger;
use Faecie\Dominos\Service\GameStatusReader\GameEndedStatusReader;
use Faecie\Dominos\Service\GameStatusReader\GamePlayedStatusReader;
use Faecie\Dominos\Service\GameStatusReader\GameStartedStatusReader;
use Faecie\Dominos\Service\GameStatusReader\PlayerDrawsTileStatusReader;
use Faecie\Dominos\Service\GameStatusReader\PlayerMissedRoundStatusReader;
use Faecie\Dominos\Service\GameStatusReader\PlayerPutTileStatusReader;

require __DIR__ . '/../vendor/autoload.php';

$gameStock = new ClassicDominoStock(new RandomizedStock());

$player1 = new SimplePlayer(
    'Alice',
    new ClassicDominoPlayerStock($gameStock, new RandomizedStock()),
    new FirstMatchingTileStrategy(),
);

$player2 = new SimplePlayer(
    'Bob',
    new ClassicDominoPlayerStock($gameStock, new RandomizedStock()),
    new FirstMatchingTileStrategy(),
);

$logger = new GameLogger([
    new PlayerDrawsTileStatusReader(),
    new PlayerPutTileStatusReader(),
    new PlayerMissedRoundStatusReader(),
    new GameStartedStatusReader(),
    new GameEndedStatusReader(),
    new GamePlayedStatusReader(),
]);

$player1->attach($logger);
$player2->attach($logger);

$game = new ClassicGame(new SimpleQueue([$player1, $player2]), $gameStock, new Board($gameStock->pickTile()));
$game->attach($logger);

while ($game->getStatus() !== GameStatusesEnum::ENDED) {
    $game->play();

    foreach ($logger->flashLogs() as $line) {
        fwrite(STDOUT, PHP_EOL . $line);
    }
}

