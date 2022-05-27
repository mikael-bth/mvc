<?php

namespace App\Card;

class GamePoker
{
    private Player $computer;
    private Player $player;
    private Player $table;
    private int $state;
    private int $playedState;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->computer = new Player("Dator");
        $this->player = new Player("Du");
        $this->table = new Player("table");
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
     * Draws cards for the player object, updates the deck accordingly.
     */
    public function drawCards(Player $player, int $numDraws, Deck $deck): Deck
    {
        $cards = $deck->drawCards($numDraws);
        $player->addCards($cards);
        return $deck;
    }

    public function getHandValue(Player $player): array
    {
        $ruleValue = 0;
        $highValue = 0;

        return [$ruleValue, $highValue];
    }
}
