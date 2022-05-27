<?php

namespace Test\HandValue;

use App\Card\Card;
use App\Card\HandValue;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class HandValue.
 */
class HandValueTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed objects
     * is of the instance 'Deal'.
     */
    public function testObjectConstruction()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 13, '♣', 'heart');
        $testCard3 = new Card("3", 12, '♣', 'club');
        $testCard4 = new Card("3", 11, '♣', 'heart');
        $testCard5 = new Card("3", 10, '♣', 'heart');
        $testCard6 = new Card("3", 3, '♣', 'heart');
        $testCard7 = new Card("3", 5, '♣', 'heart');
        $testHand = [$testCard, $testCard2, $testCard3, $testCard4, $testCard5, $testCard6, $testCard7];
        $handValue = new HandValue($testHand);
        $this->assertEquals([true, 14], $handValue->testRule());
    }
}
