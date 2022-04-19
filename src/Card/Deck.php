<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    protected $deck = [];
    private $value_list = ['A','2','3','4','5','6','7','8','9','10','J','Q','K'];
    private $color_list = ['heart', 'spade', 'diamond', 'club'];
    private $icon_list = ['♥', '♠', '♦', '♣'];

    public function __construct()
    {
        foreach ($this->icon_list as $index => $color) {
            foreach($this->value_list as $value) {
                $this->deck[] = new Card($value, $color, $this->color_list[$index]);
            }
        }
    }
    
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function drawCard(): Card
    {
        $cardIndex = random_int(0, count($this->deck) - 1);
        array_splice($this->deck, $cardIndex, 1);
        return $this->deck[$cardIndex];
    }

    public function getAsString(): string
    {
        $str = "";
        foreach ($this->deck as $card) {
            $str .= $card->getAsString();
        }
        return $str;
    }

    public function getDeckSize(): int
    {
        return count($this->deck);
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function setDeck(array $newDeck): void
    {
        $this->deck = $newDeck;
    }
}