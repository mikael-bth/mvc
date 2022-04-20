<?php

namespace App\Card;

class Deck2Jokers extends Deck
{
    public function __construct()
    {
        parent::__construct();
        $this->deck[] = new Card('J', 'J', 'heart');
        $this->deck[] = new Card('J', 'J', 'spade');
    }
}