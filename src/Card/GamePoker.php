<?php

namespace App\Card;

class GamePoker
{
    private PokerPlayer $computer;
    private PokerPlayer $player;
    private PokerPlayer $table;
    private int $pot;
    private int $state;
    private int $playedState;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->computer = new PokerPlayer("Dator");
        $this->player = new PokerPlayer("Du");
        $this->table = new PokerPlayer("table");
        $this->pot = 0;
        $this->state = 0;
        $this->playedState = -1;
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
     * Returns the state of the game.
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * Returns the played state of the game.
     */
    public function getPState(): int
    {
        return $this->playedState;
    }

    /**
     * Sets the state of the game.
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }

    /**
     * Sets the played state of the game.
     */
    public function setPState(int $state): void
    {
        $this->playedState = $state;
    }

    /**
     * Returns the pot.
     */
    public function getPot(): int
    {
        return $this->pot;
    }

    /**
     * Sets the pot.
     */
    public function setPot(int $pot): void
    {
        $this->pot = $pot;
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
