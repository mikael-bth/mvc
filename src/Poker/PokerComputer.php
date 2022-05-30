<?php

namespace App\Poker;

use App\Card\Card;

class PokerComputer
{
    private int $bluffValue;

    /**
     * Constructor
     * @param Card[] $hand
     */
    public function __construct(array $hand)
    {
        $handNumbers = array_map(function (Card $card) {
            return $card->getNumberValue();
        }, $hand);

        $this->bluffValue = random_int(0, 100);
        if ($this->getPair($handNumbers)) {
            $this->bluffValue -= 20;
        }
        if ($this->getValue($handNumbers) > 18) {
            $this->bluffValue -= 10;
        }
        error_log("bluff: " . strval($this->bluffValue));
    }

    /**
     * Returns the computers next action
     * in an int.
     */
    public function getAction(int $state): int
    {
        if ($state == 0) {
            if ($this->bluffValue < 40) {
                return 1;
            } elseif ($this->bluffValue < 80) {
                return 0;
            } else {
                return 2;
            }
        } elseif ($state == 1) {
            if ($this->bluffValue < 35) {
                return 1;
            } elseif ($this->bluffValue < 75) {
                return 0;
            } else {
                return 2;
            }
        } elseif ($state > 1) {
            if ($this->bluffValue < 30) {
                return 1;
            } elseif ($this->bluffValue < 70) {
                return 0;
            } else {
                return 2;
            }
        }
    }

    /**
     * Returns a random bet
     * amount for the computer
     */
    public function getBetAmount(int $playerBet, int $computerMoney): int
    {
        if ($playerBet > $computerMoney) {
            return $computerMoney;
        }
        $betRange = random_int(0, 20);
        if ($betRange <= 10 && $playerBet < $computerMoney * 0.25) {
            $randomBet = random_int($playerBet, $computerMoney * 0.25);
        } elseif ($betRange <= 17 && $playerBet < $computerMoney * 0.50) {
            $randomBet = random_int($playerBet, $computerMoney * 0.50);
        } else {
            $randomBet = random_int($playerBet, $computerMoney);
        }
        error_log("bet range: " . $betRange);
        $randomBet -= $randomBet % 5;
        return $randomBet;
    }

    /**
     * Returns true if hand has an pair.
     * @param int[] $hand
     * @return bool $isPair
     */
    private function getPair(array $hand): bool
    {
        $numberCount = array_count_values($hand);
        $pair = array_filter($numberCount, function($value) {
            return $value == 2;
        });

        if (count($pair) < 1) return false;

        return true;
    }

    /**
     * Returns the hands value.
     * @param int[] $hand
     * @return int $handSum
     */
    private function getValue(array $hand): int
    {
        return array_sum($hand);
    }

}
