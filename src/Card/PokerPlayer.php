<?php

namespace App\Card;

class PokerPlayer extends Player
{

    private int $bet;

    /**
     * Constructor
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->bet = 0;
    }
    
    /**
     * Retuns the players bet.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Sets the players bet.
     */
    public function setBet(int $bet): void
    {
        $this->bet = $bet;
    }
}
