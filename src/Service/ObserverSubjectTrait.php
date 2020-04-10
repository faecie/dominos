<?php

declare(strict_types=1);

namespace Faecie\Dominos\Service;

use SplObserver;

trait ObserverSubjectTrait
{
    /**
     * @var SplObserver[]
     */
    private array $observers = [];

    public function attach(SplObserver $observer): void
    {
        $this->detach($observer);
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer): void
    {
        $updatedObservers = [];
        foreach ($this->observers as $existingObserver) {
            if ($existingObserver !== $observer) {
                $updatedObservers[] = $existingObserver;
            }
        }

        $this->observers = $updatedObservers;
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
