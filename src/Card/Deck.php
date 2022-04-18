<?php

namespace App\Card;

use App\Card\Card;

class DiceHand
{
    private $deck = [];

    public function __construct()
    {
        $this->deck[] = new Card;
    }

    public function shuffle(): void
    {
        foreach ($this->deck as $card) {
            $card->roll();
        }
    }

    public function getAsString(): string
    {
        $str = "";
        foreach ($this->deck as $card) {
            $str .= $card->getAsString();
        }
        return $str;
    }
}