<?php

namespace Test\Game;

use App\Card\Game;
use App\Card\Player;
use App\Card\Deck;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class GameTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed object
     * is of the instance 'Game'.
     */
    public function testObjectConstruction()
    {
        $game = new Game();
        $this->assertInstanceOf(Game::class, $game);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct player object when getBank is called.
     */
    public function testGetBank()
    {
        $game = new Game();
        $exp = new Player("Bank");
        $res = $game->getBank();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct player object when getPlayer is called.
     */
    public function testGetPlayer()
    {
        $game = new Game();
        $exp = new Player("Dina kort");
        $res = $game->getPlayer();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed objects
     * player object gets a card when drawPlayer gets called, also
     * tests that the deck decrease after draw.
     */
    public function testDrawCardPlayer()
    {
        $game = new Game();
        $deck = new Deck();
        $expDeck = 51;
        $expDraw = 1;
        $resDeck = $game->drawPlayer($deck);
        $resDeck = $resDeck->getDeckSize();
        $resDraw = count($game->getPlayer()->getHand());
        $this->assertEquals($expDeck, $resDeck);
        $this->assertEquals($expDraw, $resDraw);
    }

    /**
     * Constructs object and tests that the constructed objects
     * bank object gets cards when drawBank gets called, also
     * tests that the deck decrease after draw.
     */
    public function testDrawCardBank()
    {
        $game = new Game();
        $deck = new Deck();
        $expDeck = 52;
        $expDraw = 0;
        $resDeck = $game->drawBank($deck);
        $resDeck = $resDeck->getDeckSize();
        $resDraw = count($game->getBank()->getHand());
        $this->assertLessThan($expDeck, $resDeck);
        $this->assertGreaterThan($expDraw, $resDraw);
    }

    /**
     * Constructs object and tests that the constructed object
     * playerStanding value is false if playerStay is not called.
     */
    public function testNotStandingStatus()
    {
        $game = new Game();
        $exp = false;
        $res = $game->getPlayerStatus();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * playerStanding value is false if playerStay is not called.
     */
    public function testStandingStatus()
    {
        $game = new Game();
        $game->playerStay();
        $exp = true;
        $res = $game->getPlayerStatus();
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct value when getPlayerSum is called.
     */
    public function testPlayerSum()
    {
        $game = new Game();
        $card = new Card("A", 14, '♥', 'heart');
        $mockDeck = $this->createMock(Deck::class);
        $mockDeck->method('drawCard')
            ->willReturn($card);
        $game->drawPlayer($mockDeck);
        $exp = 14;
        $res = $game->getSum($game->getPlayer());
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct value when getBankSum is called.
     */
    public function testBankSum()
    {
        $game = new Game();
        $card = new Card("9", 9, '♥', 'heart');
        $mockDeck = $this->createMock(Deck::class);
        $mockDeck->method('drawCard')
            ->willReturn($card);
        $game->drawBank($mockDeck);
        $exp = 18;
        $res = $game->getSum($game->getBank());
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed objects
     * player objects hand updates if the value goes over
     * 21 with an ace in the hand.
     */
    public function testAceAdjust()
    {
        $game = new Game();
        $card = new Card("A", 14, '♥', 'heart');
        $mockDeck = $this->createMock(Deck::class);
        $mockDeck->method('drawCard')
            ->willReturn($card);
        $game->drawPlayer($mockDeck);
        $game->drawPlayer($mockDeck);
        $exp = 15;
        $res = $game->getSum($game->getPlayer());
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed objects
     * getResult function returns the correct string
     */
    public function testGetResult()
    {
        $game = new Game();
        $card = new Card("9", 9, '♥', 'heart');
        $mockDeck = $this->createMock(Deck::class);
        $mockDeck->method('drawCard')
            ->willReturn($card);
        $game->drawPlayer($mockDeck);
        $game->drawBank($mockDeck);
        $exp = "Banken vann, Banken var närmare 21 än dig";
        $res = $game->getResult();
        $this->assertEquals($exp, $res);
    }
}
