<?php

declare(strict_types=1);

namespace Faecie\Dominos\Aggregate;

use Faecie\Dominos\Entity\DominoPlayer;
use IteratorAggregate;

interface PlayersQueue extends IteratorAggregate
{
    public function nextPlayer(): ?DominoPlayer;
}
