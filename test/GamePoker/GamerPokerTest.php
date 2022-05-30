<?php

namespace Test\GamePoker;

use App\Poker\GamePoker;
use App\Card\Player;
use App\Card\Deck;
use App\Card\Card;
use App\Entity\PokerGame;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class GamePokerTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed object
     * is of the instance 'GamePoker'.
     */
    public function testObjectConstruction()
    {
        $game = new GamePoker();
        $this->assertInstanceOf(GamePoker::class, $game);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct object when getComputer is called.
     */
    public function testGetComputer()
    {
        $game = new GamePoker();
        $res = $game->getComputer();
        $this->assertInstanceOf(Player::class, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct object when getPlayer is called.
     */
    public function testGetPlayer()
    {
        $game = new GamePoker();
        $res = $game->getPlayer();
        $this->assertInstanceOf(Player::class, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct object when getTable is called.
     */
    public function testGetTable()
    {
        $game = new GamePoker();
        $res = $game->getTable();
        $this->assertInstanceOf(Player::class, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct int when getState is called.
     */
    public function testGetState()
    {
        $game = new GamePoker();
        $res = $game->getState();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct int when getPState is called.
     */
    public function testGetPState()
    {
        $game = new GamePoker();
        $res = $game->getPState();
        $exp = -1;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct int when getState is called after
     * setState has been called.
     */
    public function testSetState()
    {
        $game = new GamePoker();
        $game->setState(2);
        $res = $game->getState();
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct int when getPState is called after
     * setPState has been called.
     */
    public function testSetPState()
    {
        $game = new GamePoker();
        $game->setPState(2);
        $res = $game->getPState();
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct string when getMessage is called.
     */
    public function testGetMessage()
    {
        $game = new GamePoker();
        $res = $game->getMessage();
        $exp = "";
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct string when getMessage is called after
     * setMessage has been called.
     */
    public function testSetMessage()
    {
        $game = new GamePoker();
        $game->setMessage("test");
        $res = $game->getMessage();
        $exp = "test";
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct amount of cards after drawCards is
     * called.
     */
    public function testDrawCards()
    {
        $game = new GamePoker();
        $testDeck = new Deck();
        $testDeck = $game->drawCards($game->getPlayer(), 2, $testDeck);
        $exp = 50;
        $this->assertEquals($exp, $testDeck->getDeckSize());
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct result when getResults
     * is called.
     */
    public function testGetResultsPWin()
    {
        $player = new Player("test1");
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 13, '♣', 'heart');
        $player->addCards([$testCard, $testCard2]);

        $computer = new Player("test2");
        $testCard3 = new Card("A", 4, '♥', 'heart');
        $testCard4 = new Card("3", 4, '♣', 'heart');

        $computer->addCards([$testCard3, $testCard4]);
        
        $testCard5 = new Card("3", 12, '♣', 'heart');
        $testCard6 = new Card("3", 11, '♣', 'heart');
        $testCard7 = new Card("3", 10, '♣', 'heart');
        $testCard8 = new Card("3", 2, '♣', 'club');
        $testCard9 = new Card("3", 3, '♣', 'club');
        $tableHand = [$testCard5, $testCard6,
        $testCard7, $testCard8, $testCard9];

        $table = new Player("test3");
        $table->addCards($tableHand);

        $game = new GamePoker();
        $res = $game->getResults($player, $computer, $table);
        $exp = 0;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct result when getResults
     * is called.
     */
    public function testGetResultsCWin()
    {
        $player = new Player("test1");
        $testCard = new Card("A", 4, '♥', 'heart');
        $testCard2 = new Card("3", 4, '♣', 'heart');
        $player->addCards([$testCard, $testCard2]);

        $computer = new Player("test2");
        $testCard3 = new Card("A", 14, '♥', 'heart');
        $testCard4 = new Card("3", 13, '♣', 'heart');

        $computer->addCards([$testCard3, $testCard4]);
        
        $testCard5 = new Card("3", 12, '♣', 'heart');
        $testCard6 = new Card("3", 11, '♣', 'heart');
        $testCard7 = new Card("3", 10, '♣', 'heart');
        $testCard8 = new Card("3", 2, '♣', 'club');
        $testCard9 = new Card("3", 3, '♣', 'club');
        $tableHand = [$testCard5, $testCard6,
        $testCard7, $testCard8, $testCard9];

        $table = new Player("test3");
        $table->addCards($tableHand);

        $game = new GamePoker();
        $res = $game->getResults($player, $computer, $table);
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct result when getResults
     * is called.
     */
    public function testGetResultsHighCardsWin()
    {
        $player = new Player("test1");
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 9, '♣', 'heart');
        $player->addCards([$testCard, $testCard2]);

        $computer = new Player("test2");
        $testCard3 = new Card("A", 14, '♥', 'heart');
        $testCard4 = new Card("3", 4, '♣', 'heart');

        $computer->addCards([$testCard3, $testCard4]);
        
        $testCard5 = new Card("3", 12, '♣', 'club');
        $testCard6 = new Card("3", 11, '♣', 'heart');
        $testCard7 = new Card("3", 7, '♣', 'club');
        $testCard8 = new Card("3", 2, '♣', 'club');
        $testCard9 = new Card("3", 3, '♣', 'club');
        $tableHand = [$testCard5, $testCard6,
        $testCard7, $testCard8, $testCard9];

        $table = new Player("test3");
        $table->addCards($tableHand);

        $game = new GamePoker();
        $res = $game->getResults($player, $computer, $table);
        $exp = 0;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct result when getResults
     * is called.
     */
    public function testGetResultsTie()
    {
        $player = new Player("test1");
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 14, '♣', 'heart');
        $player->addCards([$testCard, $testCard2]);

        $computer = new Player("test2");
        $testCard3 = new Card("A", 14, '♥', 'heart');
        $testCard4 = new Card("3", 14, '♣', 'heart');

        $computer->addCards([$testCard3, $testCard4]);
        
        $testCard5 = new Card("3", 14, '♣', 'club');
        $testCard6 = new Card("3", 11, '♣', 'heart');
        $testCard7 = new Card("3", 11, '♣', 'club');
        $testCard8 = new Card("3", 2, '♣', 'club');
        $testCard9 = new Card("3", 3, '♣', 'club');
        $tableHand = [$testCard5, $testCard6,
        $testCard7, $testCard8, $testCard9];

        $table = new Player("test3");
        $table->addCards($tableHand);

        $game = new GamePoker();
        $res = $game->getResults($player, $computer, $table);
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

    /**
     * Constructs object and tests that the constructed object
     * returns the correct result when getResults
     * is called.
     */
    public function testGetResultsTieRoyal()
    {
        $player = new Player("test1");
        $testCard = new Card("A", 14, '♥', 'heart');
        $testCard2 = new Card("3", 13, '♣', 'heart');
        $player->addCards([$testCard, $testCard2]);

        $computer = new Player("test2");
        $testCard3 = new Card("A", 14, '♥', 'heart');
        $testCard4 = new Card("3", 13, '♣', 'heart');

        $computer->addCards([$testCard3, $testCard4]);
        
        $testCard5 = new Card("3", 12, '♣', 'heart');
        $testCard6 = new Card("3", 11, '♣', 'heart');
        $testCard7 = new Card("3", 10, '♣', 'heart');
        $testCard8 = new Card("3", 9, '♣', 'heart');
        $testCard9 = new Card("3", 8, '♣', 'heart');
        $tableHand = [$testCard5, $testCard6,
        $testCard7, $testCard8, $testCard9];

        $table = new Player("test3");
        $table->addCards($tableHand);

        $game = new GamePoker();
        $res = $game->getResults($player, $computer, $table);
        $exp = 2;
        $this->assertEquals($exp, $res);
    }

}
