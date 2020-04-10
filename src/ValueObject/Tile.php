<?php

declare(strict_types=1);

namespace Faecie\Dominos\ValueObject;

class Tile
{
    private $leftSide;
    private $rightSide;

    public function __construct(int $leftSide, int $rightSide)
    {
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
    }

    public function getLeftSide(): int
    {
        return $this->leftSide;
    }

    public function getRightSide(): int
    {
        return $this->rightSide;
    }

    public function reverse(): void
    {
        [$this->rightSide, $this->leftSide] = [$this->leftSide, $this->rightSide];
    }

    public function toString(): string
    {
        return "<{$this->leftSide}:{$this->rightSide}>";
    }
}
