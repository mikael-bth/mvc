<?php

namespace Test\GamePoker;

use App\Poker\PokerComputer;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerComputer.
 */
class PokerComputerTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed object
     * is of the instance 'PokerComputer'.
     */
    public function testObjectConstruction()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        $pokerComputer = new PokerComputer($testHand);
        $this->assertInstanceOf(PokerComputer::class, $pokerComputer);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState0Call()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(12345);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 0;
        $res = $pokerComputer->getAction(0);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState0Bet()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(222);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 1;
        $res = $pokerComputer->getAction(0);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState0Fold()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        srand(12333);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 2;
        $res = $pokerComputer->getAction(0);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState1Call()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(12345);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 0;
        $res = $pokerComputer->getAction(1);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState1Bet()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(222);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 1;
        $res = $pokerComputer->getAction(1);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState1Fold()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        srand(12333);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 2;
        $res = $pokerComputer->getAction(1);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState2Call()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(12345);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 0;
        $res = $pokerComputer->getAction(2);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState2Bet()
    {
        $testCard = new Card("A", 14, '♥', 'heart');
        $testHand = [$testCard, $testCard];
        srand(222);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 1;
        $res = $pokerComputer->getAction(21);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct action when getAction is called.
     */
    public function testGetActionState2Fold()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        srand(12333);
        $pokerComputer = new PokerComputer($testHand);
        $exp = 2;
        $res = $pokerComputer->getAction(2);
        $this->assertEquals($exp, $res);
    }
    
    /**
     * Constructs object and tests that the constructed object
     * returns the correct bet amount when getBetAmount is called.
     */
    public function testGetBetAllIn()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        $pokerComputer = new PokerComputer($testHand);
        $exp = 80;
        $res = $pokerComputer->getBetAmount(100, 80);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct bet amount when getBetAmount is called.
     */
    public function testGetBetLow()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        $pokerComputer = new PokerComputer($testHand);
        $exp = 35;
        srand(232);
        $res = $pokerComputer->getBetAmount(10, 200);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct bet amount when getBetAmount is called.
     */
    public function testGetBetMid()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        $pokerComputer = new PokerComputer($testHand);
        $exp = 50;
        srand(425);
        $res = $pokerComputer->getBetAmount(10, 200);
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct bet amount when getBetAmount is called.
     */
    public function testGetBetHigh()
    {
        $testCard = new Card("A", 2, '♥', 'heart');
        $testCard1 = new Card("A", 4, '♥', 'heart');
        $testHand = [$testCard, $testCard1];
        $pokerComputer = new PokerComputer($testHand);
        $exp = 195;
        srand(3213221);
        $res = $pokerComputer->getBetAmount(150, 200);
        $this->assertEquals($exp, $res);
    }

}
