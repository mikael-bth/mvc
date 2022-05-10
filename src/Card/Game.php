<?php

namespace App\Card;

class Game
{
    private Player $bank;
    private Player $player;
    private bool $playerStanding;

    public function __construct()
    {
        $this->bank = new Player("Bank");
        $this->player = new Player("Dina kort");
        $this->playerStanding = false;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function drawPlayer(Deck $deck): Deck
    {
        $card = $deck->drawCard();
        $this->player->addCard($card);
        return $deck;
    }

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

    public function playerStay()
    {
        $this->playerStanding = true;
    }

    public function getPlayerStatus(): bool
    {
        return $this->playerStanding;
    }

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
