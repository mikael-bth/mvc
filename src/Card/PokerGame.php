<?php

namespace App\Card;

class PokerGame
{
    private Player $computer;
    private Player $player;
    private Player $table;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->computer = new Player("Dator");
        $this->player = new Player("Dina kort");
        $this->player = new Player("table");
    }

    /**
     * Returns the computer object.
     */
    public function getComputer(): Player
    {
        return $this->computer;
    }

    /**
     * Returns the player object.
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Returns the table object.
     */
    public function getTable(): Player
    {
        return $this->table;
    }

    /**
     * Draws cards for the player object, updates the deck accordingly.
     */
    public function drawCards(Player $player, int $numDraws, Deck $deck): Deck
    {
        $cards = $deck->drawCards($numDraws);
        $player->addCards($cards);
        return $deck;
    }
}
