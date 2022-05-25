<?php

namespace App\Card;

use App\Card\Card;

/**
 * Deck class
 * 
 * @property Card[] $deck
 * @property array<string> $valueList
 * @property array<int> $valueNumberList
 * @property array<string> $colorList
 * @property array<string> $iconList
 */
class Deck
{
    protected array $deck = [];
    private array $valueList = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
    private array $valueNumberList = [2,3,4,5,6,7,8,9,10,11,12,13,14];
    private array $colorList = ['heart', 'spade', 'diamond', 'club'];
    private array $iconList = ['♥', '♠', '♦', '♣'];

    /**
     * Constructor
     */
    public function __construct()
    {
        foreach ($this->iconList as $colorIndex => $color) {
            foreach ($this->valueList as $valueIndex => $value) {
                $this->deck[] = new Card(
                    $value,
                    $this->valueNumberList[$valueIndex],
                    $color,
                    $this->colorList[$colorIndex]
                );
            }
        }
    }

    /**
     * Shuffles the deck array.
     */
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * Returns a random card, then removes that card from the deck.
     */
    public function drawCard(): Card
    {
        $deckSize = $this->getDeckSize();
        $cardIndex = random_int(0, $deckSize - 1);
        $card = $this->deck[$cardIndex];
        array_splice($this->deck, $cardIndex, 1);
        return $card;
    }

    /**
     * Returns a random list with specified amount of cards,
     * then removes those card from the deck.
     */
    public function drawCards(int $numDraws): array
    {
        $cardList = [];
        for ($i = 0; $i < $numDraws; $i++) {
            $card = $this->drawCard();
            $cardList[] = $card;
        }
        return $cardList;
    }

    /**
     * Returns the deck array as a string.
     */
    public function getAsString(): string
    {
        $str = "";
        foreach ($this->deck as $card) {
            $str .= $card->getAsString();
        }
        return $str;
    }

    /**
     * Returns how many card are in the deck array.
     */
    public function getDeckSize(): int
    {
        return count($this->deck);
    }

    /**
     * Returns the deck array.
     * @return array<Card> $deck
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Sets the deck array to a given array.
     * @param array<Card> $newDeck
     */
    public function setDeck(array $newDeck): void
    {
        $this->deck = $newDeck;
    }
}
