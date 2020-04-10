<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate;

use ArrayIterator;
use Faecie\Dominos\Entity\DominoPlayer;
use InfiniteIterator;
use Traversable;

class SimpleQueue implements PlayersQueue
{
    private InfiniteIterator $queue;
    private array $players;

    public function __construct(array $players)
    {
        $this->players = $players;
        $this->queue = new InfiniteIterator(new ArrayIterator($this->players));
        $this->queue->rewind();
    }

    public function nextPlayer(): ?DominoPlayer
    {
        $result = $this->queue->current();
        $this->queue->next();

        return $result;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->players);
    }
}
