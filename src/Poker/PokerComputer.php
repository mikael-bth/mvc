<?php

namespace App\Poker;

use App\Card\Card;

class PokerComputer
{
    /**
     * Returns the computers next move
     * in an int.
     * @param Card[] $hand
     */
    public function getMove(array $hand, int $state): int
    {
        $handNumbers = array_map(function (Card $card) {
            return $card->getNumberValue();
        }, $hand);

        rsort($handNumbers);

        $bluffValue = random_int(0, 100);
        if ($state == 0) {
            if ($this->getPair($handNumbers) || $bluffValue < 20) {
                return 1;
            } elseif ($this->getValue($handNumbers) > 18 || $bluffValue < 40) {
                return 0;
            } else {
                return 2;
            }
        } elseif ($state == 1) {
            if ($bluffValue < 30) {
                return 1;
            } elseif ($bluffValue < 70) {
                return 0;
            } else {
                return 2;
            }
        } elseif ($state > 1) {
            if ($bluffValue < 50) {
                return 1;
            } elseif ($bluffValue < 80) {
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
        $randomBet = random_int($playerBet, $computerMoney);
        $randomBet -= $randomBet % 5;
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
