<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoGame;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\GameStatusesEnum;

class GamePlayedStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        return sprintf('Board is now: %s', $objectWithStatus->getBoard()->toString());
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        return $objectWithStatus instanceof DominoGame && $objectWithStatus->getStatus() === GameStatusesEnum::PLAYED;
    }
}
