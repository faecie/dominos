<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Aggregate;

use Faecie\Dominos\Aggregate\SimpleQueue;
use Faecie\Dominos\Entity\DominoPlayer;
use PHPUnit\Framework\TestCase;

class SimpleQueueTest extends TestCase
{
    public function testCanIterateJustLikeAnArray(): void
    {
        $players = array_map(fn() => $this->getMockForAbstractClass(DominoPlayer::class), range(0, 5));
        $sut = new SimpleQueue($players);

        $result = iterator_to_array($sut, false);
        $this->assertCount(6, $result);
    }

    public function testCanTellWhoIsNext(): void
    {
        $player1 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player2 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player3 = $this->getMockForAbstractClass(DominoPlayer::class);

        $sut = new SimpleQueue([$player1, $player2, $player3]);
        $queue = [];
        foreach (range(0, 13) as $order) {
            $queue[$order] = $sut->nextPlayer();
        }

        $this->assertSame($player1, $queue[0]);
        $this->assertSame($player3, $queue[2]);
        $this->assertSame($player2, $queue[4]);
        $this->assertSame($player2, $queue[13]);
    }

    public function testIterationDoesntAffectQueue(): void
    {
        $player1 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player2 = $this->getMockForAbstractClass(DominoPlayer::class);
        $player3 = $this->getMockForAbstractClass(DominoPlayer::class);

        $sut = new SimpleQueue([$player1, $player2, $player3]);
        iterator_to_array($sut, false);
        $this->assertSame($player1, $sut->nextPlayer());
        iterator_to_array($sut, false);
        $this->assertSame($player2, $sut->nextPlayer());
        iterator_to_array($sut, false);
        $this->assertSame($player3, $sut->nextPlayer());
    }
}
