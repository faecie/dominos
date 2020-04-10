<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service;

use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Service\GameStatusReader\GameStatusReader;
use SplObserver;
use SplSubject;

class GameLogger implements SplObserver
{
    private array $logs;
    private array $statusReaders;

    public function __construct(array $statusReaders)
    {
        $this->statusReaders = array_map(fn(GameStatusReader $reader) => $reader, $statusReaders);
    }

    public function flashLogs(): array
    {
        $result = $this->logs;
        $this->logs = [];

        return $result;
    }

    public function update(SplSubject $subject): void
    {
        foreach ($this->statusReaders as $reader) {
            if ($subject instanceof WithStatus && $reader->supports($subject)) {
                $this->logs[] = $reader->read($subject);
            }
        }
    }
}
