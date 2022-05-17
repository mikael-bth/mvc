<?php

namespace Test\Deck;

use App\Card\Card;
use App\Card\Deck;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed objects
     * is of the instance 'Deck'.
     */
    public function testObjectConstruction()
    {
        $deck = new Deck();
        $this->assertInstanceOf(Deck::class, $deck);
    }

    /**
     * Constructs two objects and tests that one objects deck array
     * is different after calling shuffleDeck.
     */
    public function testShuffleDeck()
    {
        $deck1 = new Deck();
        $deck2 = new Deck();
        $deck2->shuffleDeck();
        $this->assertNotEquals($deck1->getDeck(), $deck2->getDeck());
    }

    /**
     * Constructs object and tests that the constructed objects
     * deck array decreases in size after drawing card.
     */
    public function testDrawCardDecrease()
    {
        $deck = new Deck();
        $before = $deck->getDeckSize();
        $deck->drawCard();
        $after = $deck->getDeckSize();
        $this->assertLessThan($before, $after);
    }

    /**
     * Constructs object and tests that the constructed objects
     * getDeckSize function returns expected number.
     */
    public function testDeckSize()
    {
        $deck = new Deck();
        $res = $deck->getDeckSize();
        $exp = 52;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed objects
     * setDeck function changes the deck variable.
     */
    public function testSetDeck()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $exp = [$testCard];
        $deck = new Deck();
        $deck->setDeck($exp);
        $res = $deck->getDeck();
        $this->assertEquals($exp, $res);
    }

}
