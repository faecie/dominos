<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\GameStatusesEnum;

class GameStartedStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        return sprintf('Game starting with first tile: %s',
            $objectWithStatus->getBoard()->getAdjacentTile()->toString());
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        return $objectWithStatus instanceof DominoGame && $objectWithStatus->getStatus() === GameStatusesEnum::STARTED;
    }
}
