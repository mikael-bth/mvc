<?php

namespace App\Card;

class Player
{
    protected array $hand = [];
    private string $name;

    /**
     * Constructor
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Adds a card to the players hand.
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Returns the players hand.
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * Returns the players name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns true if player has an ace with numerical value of 14
     * in hand else false.
     */
    public function hasAce(): bool
    {
        foreach ($this->hand as $card) {
            if ($card->getNumberValue() == 14) {
                return true;
            }
        }
        return false;
    }
}
