<?php

declare(strict_types=1);

namespace Tests\Faecie\Dominos\Unit\Service;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Service\GameLogger;
use Faecie\Dominos\Service\GameStatusReader\GameStatusReader;
use PHPUnit\Framework\TestCase;

class GameLoggerTest extends TestCase
{
    public function testFlashesCollectedLogs(): void
    {
        $reader = $this->getMockForAbstractClass(GameStatusReader::class);
        $reader->method('supports')->willReturn(true);
        $reader->method('read')->willReturn('Test read');

        $sut = new GameLogger([$reader]);

        $sut->update($this->getMockForAbstractClass(DominoGame::class));
        $logs = $sut->flashLogs();

        $this->assertEmpty($sut->flashLogs());
        $this->assertCount(1, $logs);
        $this->assertStringContainsStringIgnoringCase('Test read', $logs[0]);
    }
}
