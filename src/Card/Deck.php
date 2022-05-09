<?php

namespace App\Card;

use App\Card\Card;
use Doctrine\Common\Collections\Expr\Value;

class Deck
{
    protected $deck = [];
    private $valueList = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
    private $valueNumberList = [2,3,4,5,6,7,8,9,10,11,12,13,14];
    private $colorList = ['heart', 'spade', 'diamond', 'club'];
    private $iconList = ['♥', '♠', '♦', '♣'];

    public function __construct()
    {
        foreach ($this->iconList as $colorIndex => $color) {
            foreach($this->valueList as $valueIndex => $value) {
                $this->deck[] = new Card($value, $this->valueNumberList[$valueIndex], $color, $this->colorList[$colorIndex]);
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