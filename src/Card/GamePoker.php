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

    /**
     * Returns an int that represents the winner of the game 
     */
    public function getResults(Player $player, Player $computer): int
    {
        $handValueP = new HandValue($player->getHand());
        $handValueC = new HandValue($computer->getHand());
        $playerHandValue = $handValueP->CalculateHandValue();
        $computerHandValue = $handValueC->CalculateHandValue();

        if ($playerHandValue[0] < $computerHandValue[0]) {
            return 1;
        } else if ($playerHandValue[0] == $computerHandValue[0]) {
            if ($playerHandValue[0] == 9) {
                return 2;
            } else {
                $playerHighCards = $playerHandValue[1];
                $computerHighCards = $computerHandValue[1];
                for ($i = 0; $i < 5; $i++) {
                    if (array_key_exists($i, $playerHighCards)) {
                        if ($playerHighCards[$i] > $computerHighCards[$i]) {
                            return 0;
                        } elseif ($playerHighCards[$i] < $computerHighCards[$i]) {
                            return 1;
                        }
                    } else {
                        return 2;
                    }  
                }
                return 2;
            }
        }
        return 0;
    }
}
