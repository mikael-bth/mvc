<?php

namespace Test\Deal;

use App\Card\Deal;
use App\Card\Deck;
use App\Card\Player;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deal.
 */
class DealTest extends TestCase
{
    /**
     * Constructs object and tests that the constructed objects
     * is of the instance 'Deal'.
     */
    public function testObjectConstruction()
    {
        $deck = new Deck();
        $deal = new Deal(2, 2, $deck);
        $this->assertInstanceOf(Deal::class, $deal);
    }

    /**
     * Constructs object and tests that getPlayers returns
     * the correct amount of players.
     */
    public function testGetPlayers()
    {
        $deck = new Deck();
        $deal = new Deal(2, 2, $deck);
        $playerList = $deal->getPlayers();
        $this->assertEquals(2, count($playerList));
    }

    /**
     * Constructs object and tests that getPlayers returns
     * the correct type of array.
     */
    public function testGetPlayersType()
    {
        $deck = new Deck();
        $deal = new Deal(2, 2, $deck);
        $playerList = $deal->getPlayers();
        $this->assertInstanceOf(Player::class, $playerList[0]);
    }
}
