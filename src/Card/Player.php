<?php

namespace App\Card;

class Player
{
    protected $hand = [];
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function getHand(): array
    {
        return $this->hand;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function hasAce()
    {
        foreach ($this->hand as $card) {
            if ($card->getNumberValue() == 14) {
                return true;
            }
        }
        return false;
    }
}