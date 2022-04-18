<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    protected $deck = [];
    private $value_list = ['A','2','3','4','5','6','7','8','9','10','J','Q','K'];
    private $color_list = ["Hearts", "Spades", "Clubs", "Diamonds"];

    public function __construct()
    {
        foreach ($this->color_list as $color) {
            foreach($this->value_list as $value) {
                $this->deck[] = new Card($value, $color);
            }
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

    public function getDeck(): array
    {
        return $this->deck;
    }
}