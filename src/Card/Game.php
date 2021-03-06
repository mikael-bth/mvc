<?php

namespace App\Card;

class Game
{
    private Player $bank;
    private Player $player;
    private bool $playerStanding;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bank = new Player("Bank");
        $this->player = new Player("Dina kort");
        $this->playerStanding = false;
    }

    /**
     * Returns the bank object.
     */
    public function getBank(): Player
    {
        return $this->bank;
    }

    /**
     * Returns the player object.
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Draws a card for the player, updates the deck accordingly.
     */
    public function drawPlayer(Deck $deck): Deck
    {
        $card = $deck->drawCard();
        $this->player->addCard($card);
        return $deck;
    }

    /**
     * Draws a card for the bank, updates the deck accordingly.
     */
    public function drawBank(Deck $deck): Deck
    {
        $bankSum = $this->getSum($this->bank);
        while ($bankSum < 17) {
            $card = $deck->drawCard();
            $this->bank->addCard($card);
            $bankSum = $this->getSum($this->bank);
        }
        return $deck;
    }

    /**
     * Changes the playerStanding bool to true.
     */
    public function playerStay(): void
    {
        $this->playerStanding = true;
    }

    /**
     * Returns the playerStanding value.
     */
    public function getPlayerStatus(): bool
    {
        return $this->playerStanding;
    }

    /**
     * Returns the sum of the cards in the players hands,
     * updates the value accordingly if the player has aces in the hand.
     */
    public function getSum(Player $player): int
    {
        $sumArray = array_map(function (Card $card) {
            return $card->getNumberValue();
        }, $player->getHand());
        $sum = intval(array_sum($sumArray));
        $sum = $this->aceAdjust($player, $sum);
        return $sum;
    }

    /**
     * Changes an ace cards value to 1 instead of 14.
     */
    public function aceAdjust(Player $player, int $oldSum): int
    {
        if ($oldSum < 21 or !$player->hasAce()) {
            return $oldSum;
        }
        $aces = array_filter($player->getHand(), function (Card $card) {
            return $card->getNumberValue() == 14;
        });
        $aces[array_key_first($aces)]->setNumberValue(1);
        return $oldSum - 13;
    }

    /**
     * Returns a string that describes the result of the game.
     */
    public function getResult(): string
    {
        $bankSum = $this->getSum($this->bank);
        $playerSum = $this->getSum($this->player);
        $message = "Du vann, du var n??rmare 21 ??n banken";
        if ($bankSum > 21) {
            $message = "Du vann, Banken gick ??ver 21";
        } elseif ($bankSum > $playerSum) {
            $message = "Banken vann, Banken var n??rmare 21 ??n dig";
        } elseif ($bankSum == $playerSum) {
            $message = "Banken vann, Banken var lika n??ra 21 som dig";
        }
        return $message;
    }
}
