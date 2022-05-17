<?php

namespace Test\Player;

use App\Card\Player;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class PlayerTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed object
     * is of the instance 'Player'.
     */
    public function testObjectConstruction()
    {
        $player = new Player("test");
        $this->assertInstanceOf(Player::class, $player);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct name when getName is called.
     */
    public function testPlayerName()
    {
        $player = new Player("test");
        $exp = "test";
        $res = $player->getName();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * hand array gets added a card when addCard is called.
     */
    public function testAddCard()
    {
        $player = new Player("test");
        $card = new Card("A", 14, '♥', 'heart');
        $exp = [$card];
        $player->addCard($card);
        $res = $player->getHand();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns a empty array when getHand is called when addCard
     * has not been called.
     */
    public function testEmptyHand()
    {
        $player = new Player("test");
        $exp = [];
        $res = $player->getHand();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and adds a ace cards to the hand and tests
     * that the constructed object returns true when hasAce is called.
     */
    public function testAceHasAce()
    {
        $player = new Player("test");
        $ace = new Card("A", 14, '♥', 'heart');
        $player->addCard($ace);
        $this->assertTrue($player->hasAce());
    }

    /**
     * Constructs object and tests that the constructed object
     * returns false when hasAce is called.
     */
    public function testNoAceHasAce()
    {
        $player = new Player("test");
        $this->assertFalse($player->hasAce());
    }
}
