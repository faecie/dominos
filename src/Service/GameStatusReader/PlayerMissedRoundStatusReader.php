<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service\GameStatusReader;

use Faecie\Dominos\Entity\DominoPlayer;
use Faecie\Dominos\Entity\WithStatus;
use Faecie\Dominos\Enum\PlayerStatusesEnum;

class PlayerMissedRoundStatusReader implements GameStatusReader
{
    public function read(WithStatus $objectWithStatus): string
    {
        if (!$this->supports($objectWithStatus)) {
            return '';
        }

        return sprintf('%s cant play', $objectWithStatus->getName());
    }

    public function supports(WithStatus $objectWithStatus): bool
    {
        return $objectWithStatus instanceof DominoPlayer &&
            $objectWithStatus->getStatus() === PlayerStatusesEnum::CANT_PLAY;
    }
}
