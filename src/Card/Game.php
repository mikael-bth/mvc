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
        $bankSum = $this->getBankSum();
        while ($bankSum < 17) {
            $card = $deck->drawCard();
            $this->bank->addCard($card);
            $bankSum = $this->getBankSum();
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
    public function getPlayerSum(): int
    {
        $sum = 0;
        foreach ($this->player->getHand() as $card) {
            $sum += $card->getNumberValue();
        }
        if ($sum > 21 and $this->player->hasAce()) {
            $sum = $this->aceAdjust($this->player, $sum);
        }
        return $sum;
    }

    /**
     * Returns the sum of the cards in the banks hands,
     * updates the value accordingly if the bank has aces in the hand.
     */
    public function getBankSum(): int
    {
        $sum = 0;
        foreach ($this->bank->getHand() as $card) {
            $sum += $card->getNumberValue();
        }
        if ($sum > 21 and $this->bank->hasAce()) {
            $sum = $this->aceAdjust($this->bank, $sum);
        }
        return $sum;
    }

    /**
     * Changes an ace cards value to 1 instead of 14.
     */
    public function aceAdjust(Player $player, int $oldSum): int
    {
        foreach ($player->getHand() as $card) {
            if ($card->getNumberValue() == 14) {
                $card->setNumberValue(1);
                return $oldSum - 13;
            }
        }
        return $oldSum;
    }
}
