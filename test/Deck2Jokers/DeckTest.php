<?php

namespace Test\Deck2Jokers;

use App\Card\Deck2Jokers;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck2Jokers.
 */
class DeckTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed object
     * is of the instance 'Deck2Jokers'.
     */
    public function testObjectConstruction()
    {
        $deck = new Deck2Jokers();
        $this->assertInstanceOf(Deck2Jokers::class, $deck);
    }

    /**
     * Constructs object and tests that the constructed object
     * contains joker card.
     */
    public function testContainsJoker()
    {
        $deck = new Deck2Jokers();
        $joker = new Card('J', 0, 'J', 'heart');
        $this->assertEquals($joker, $deck->getDeck()[52]);
    }
}
