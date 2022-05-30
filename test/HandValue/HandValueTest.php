<?php

namespace Test\HandValue;

use App\Card\Card;
use App\Poker\HandValue;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class HandValue.
 */
class HandValueTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed objects
     * is of the instance 'HandValue'.
     */
    public function testObjectConstruction()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $handValue = new HandValue([$testCard]);
        $this->assertInstanceOf(HandValue::class, $handValue);
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Royal Flush.
     */
    public function testRoyalFlush()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 13, '♣', 'heart');
        $testCard3 = new Card("3", 12, '♣', 'heart');
        $testCard4 = new Card("3", 11, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'heart');
        $testCard6 = new Card("3", 2, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([9, [14]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Straight Flush.
     */
    public function testStraightFlush()
    {
        $testCard = new Card("A", 4, '♥', 'heart');
        $testCard2 = new Card("3", 13, '♣', 'heart');
        $testCard3 = new Card("3", 12, '♣', 'heart');
        $testCard4 = new Card("3", 11, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'heart');
        $testCard6 = new Card("3", 9, '♣', 'heart');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([8, [13]], $handValue->calculateHandValue());
    }
    
    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Four Of A Kind.
     */
    public function testFourOfAKind()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 14, '♣', 'heart');
        $testCard3 = new Card("3", 14, '♣', 'heart');
        $testCard4 = new Card("3", 14, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'club');
        $testCard6 = new Card("3", 9, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([7, [14, 10]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Full House.
     */
    public function testFullHouse()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 14, '♣', 'heart');
        $testCard3 = new Card("3", 10, '♣', 'heart');
        $testCard4 = new Card("3", 10, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'club');
        $testCard6 = new Card("3", 9, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([6, [10, 14]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Flush.
     */
    public function testFlush()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard2 = new Card("3", 3, '♣', 'heart');
        $testCard3 = new Card("3", 5, '♣', 'heart');
        $testCard4 = new Card("3", 7, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'heart');
        $testCard6 = new Card("3", 9, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([5, [10]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Straight.
     */
    public function testStraight()
    {
        $testCard = new Card("A", 10, '♥', 'heart');
        $testCard2 = new Card("3", 9, '♣', 'club');
        $testCard3 = new Card("3", 8, '♣', 'heart');
        $testCard4 = new Card("3", 7, '♣', 'club');
        $testCard5 = new Card("3", 6, '♣', 'heart');
        $testCard6 = new Card("3", 9, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([4, [10]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Three Of A Kind.
     */
    public function testThreeOfAKind()
    {
        $testCard = new Card("A", 10, '♥', 'heart');
        $testCard2 = new Card("3", 10, '♣', 'club');
        $testCard3 = new Card("3", 10, '♣', 'heart');
        $testCard4 = new Card("3", 3, '♣', 'club');
        $testCard5 = new Card("3", 2, '♣', 'heart');
        $testCard6 = new Card("3", 9, '♣', 'club');
        $testCard7 = new Card("3", 14, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([3, [10, 14, 9]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Two Pair.
     */
    public function testTwoPair()
    {
        $testCard = new Card("A", 10, '♥', 'heart');
        $testCard2 = new Card("3", 10, '♣', 'club');
        $testCard3 = new Card("3", 8, '♣', 'heart');
        $testCard4 = new Card("3", 8, '♣', 'club');
        $testCard5 = new Card("3", 6, '♣', 'heart');
        $testCard6 = new Card("3", 6, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([2, [10, 8, 6]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Pair.
     */
    public function testPair()
    {
        $testCard = new Card("A", 10, '♥', 'heart');
        $testCard2 = new Card("3", 10, '♣', 'club');
        $testCard3 = new Card("3", 7, '♣', 'heart');
        $testCard4 = new Card("3", 8, '♣', 'club');
        $testCard5 = new Card("3", 6, '♣', 'heart');
        $testCard6 = new Card("3", 2, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([1, [10, 8, 7, 6]], $handValue->calculateHandValue());
    }

    /**
     * Constructs object and tests that the constructed objects
     * calculateHand function returns the correct value when
     * hand is a Pair.
     */
    public function testHighCard()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 12, '♣', 'club');
        $testCard3 = new Card("3", 11, '♣', 'heart');
        $testCard4 = new Card("3", 9, '♣', 'club');
        $testCard5 = new Card("3", 6, '♣', 'heart');
        $testCard6 = new Card("3", 2, '♣', 'club');
        $testCard7 = new Card("3", 3, '♣', 'club');
        $testHand = [$testCard, $testCard2,
        $testCard3, $testCard4, $testCard5,
        $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([0, [14, 12, 11, 9, 6]], $handValue->calculateHandValue());
    }

}
