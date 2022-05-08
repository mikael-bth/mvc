<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    protected $deck = [];
    private $valueList = ['A','2','3','4','5','6','7','8','9','10','J','Q','K'];
    private $colorList = ['heart', 'spade', 'diamond', 'club'];
    private $iconList = ['♥', '♠', '♦', '♣'];

    public function __construct()
    {
        foreach ($this->iconList as $index => $color) {
            foreach($this->valueList as $value) {
                $this->deck[] = new Card($value, $color, $this->colorList[$index]);
            }
        }
    }
    
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    public function drawCard(): Card
    {
        $deckSize = $this->getDeckSize();
        $cardIndex = random_int(0, $deckSize - 1);
        $card = $this->deck[$cardIndex];
        array_splice($this->deck, $cardIndex, 1);
        return $card;
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