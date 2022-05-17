<?php

namespace Test\Card;

use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed objects
     * is of the instance 'Card'.
     */
    public function testObjectConstruction()
    {
        $card = new Card("A", 14, '♥', 'heart');
        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct string value when getAsString is called.
     */
    public function testStringValue()
    {
        $card = new Card("A", 14, '♥', 'heart');
        $res = $card->getAsString();
        $exp = "A | ♥";
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct string value when getAsJSONString is called.
     */
    public function testJSONStringValue()
    {
        $card = new Card("A", 14, '♥', 'heart');
        $res = $card->getAsJSONString();
        $exp = "A | heart";
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct string value when getColorName is called.
     */
    public function testColorNameValue()
    {
        $card = new Card("A", 14, '♥', 'heart');
        $res = $card->getColorName();
        $exp = 'heart';
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct number value when getNumberValue is called.
     */
    public function testNumberValue()
    {
        $card = new Card("A", 14, '♥', 'heart');
        $res = $card->getNumberValue();
        $exp = 14;
        $this->assertEquals($exp, $res);
    }
}
