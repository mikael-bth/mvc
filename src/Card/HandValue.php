<?php

namespace App\Card;

class HandValue
{
    private array $handNumbers;
    private array $handColors;
    private array $hand;

    /**
     * Constructor
     * @param array<Card> $hand
     */
    public function __construct(array $hand)
    {
        $this->hand = $hand;

        $this->handNumbers = array_map(function (Card $card) {
            return $card->getNumberValue();
        }, $hand);
        if (in_array(14, $this->handNumbers)) {
            $this->handNumbers[] = 1;
        }
        rsort($this->handNumbers);

        $this->handColors = array_map(function (Card $card) {
            return $card->getColorName();
        }, $hand);
    }

    /**
     * Calculates the hands value and returns
     * an array of two ints where the first
     * int represent the rule value
     * exampel: pair is 1, two-pair is 2
     * and the second int represent the high
     * value
     * exampel: card with value 7 is 7
     */
    public function CalculateHandValue(): array
    {
        return [$this->ruleValue, $this->highValue];
    }

    public function testRule(): array
    {
        return $this->RoyalFlush();
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an royal-flush and an int that
     * is the highest card of the royal-flush,
     * 0 if bool is false.
     * @return array<bool, int>
     */
    private function RoyalFlush(): array
    {
        $colors = array_count_values($this->handColors);
        $colors = array_filter($colors, function($value) {
            return $value > 4;
        });

        if (count($colors) == 0) return [false, 0];

        $color = array_key_first($colors);
        $colorsNumberValue  = array_map(
            function (Card $card) use ($color) 
            {
                if ($card->getColorName() == $color) {
                    return $card->getNumberValue();
                }
        }, $this->hand);

        $royalNumbers = [14, 13, 12, 11, 10];

        if (count(array_intersect($colorsNumberValue, $royalNumbers)) == 5) {
            return [true, 14];
        }
        
        return [false, 0];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an straight-flush and an int that
     * is the highest card of the straight-flush,
     * 0 if bool is false.
     * @return array<bool, int>
     */
    private function StraightFlush(): array
    {
        $colors = array_count_values($this->handColors);
        $colors = array_filter($colors, function($value) {
            return $value > 4;
        });

        if (count($colors) == 0) return [false, 0];

        $color = array_key_first($colors);
        $colorsNumberValue  = array_map(
            function (Card $card) use ($color) 
            {
                if ($card->getColorName() == $color) {
                    return $card->getNumberValue();
                }
        }, $this->hand);

        if (in_array(14, $colorsNumberValue)) {
            $colorsNumberValue[] = 1;
        }

        rsort($colorsNumberValue);

        $highCard = 0;
        $straightFlushValue = 0;
        $prevCard = 0;

        $numbersLen = count($colorsNumberValue) - 1;

        for ($i = 0; $i <= $numbersLen; $i++) {
            $cardNumber = $colorsNumberValue[$i];

            if ($prevCard == $cardNumber + 1 || $prevCard == 0) {
                if ($i == $numbersLen) {
                    $straightFlushValue += 1;
                } elseif ($cardNumber - 1 == $this->handNumbers[$i + 1]) {
                    $straightFlushValue += 1;
                    $highCard = ($prevCard == 0) ? $cardNumber : $highCard;
                    $prevCard = $cardNumber;
                } else {
                    $prevCard = 0;
                    $straightFlushValue = 0;
                }
            
                if ($straightFlushValue == 5) {
                    return [true, $highCard];
                }
            } else {
                $straightFlushValue = 0;
                $prevCard = 0;
            }
        }
        return [false, 0];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an flush and an int that
     * is the highest card of the flush,
     * 0 if bool is false.
     * @return array<bool, int>
     */
    private function Flush(): array
    {
        $colors = array_count_values($this->handColors);
        $colors = array_filter($colors, function($value) {
            return $value > 4;
        });
        if (count($colors) > 0) {
            $color = array_key_first($colors);
            $colorsNumberValue  = array_map(
                function (Card $card) use ($color) 
                {
                    if ($card->getColorName() == $color) {
                        return $card->getNumberValue();
                    }
            }, $this->hand);
            rsort($colorsNumberValue);
            $highCard = $colorsNumberValue[0];
            return [true, $highCard];
        }
        return [false, 0];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an straight and an int that
     * is the highest card of the straight,
     * 0 if bool is false.
     * @return array<bool, int>
     */
    private function Straight(): array
    {
        $highCard = 0;
        $straightValue = 0;
        $prevCard = 0;

        $numbersLen = count($this->handNumbers) - 1;

        for ($i = 0; $i <= $numbersLen; $i++) {
            $cardNumber = $this->handNumbers[$i];

            if ($prevCard == $cardNumber + 1 || $prevCard == 0) {
                if ($i == $numbersLen) {
                    $straightValue += 1;
                } elseif ($cardNumber - 1 == $this->handNumbers[$i + 1]) {
                    $straightValue += 1;
                    $highCard = ($prevCard == 0) ? $cardNumber : $highCard;
                    $prevCard = $cardNumber;
                } else {
                    $prevCard = 0;
                    $straightValue = 0;
                }
                
                if ($straightValue == 5) {
                    return [true, $highCard];
                }
            } else {
                $straightValue = 0;
                $prevCard = 0;
            }
        }
        return [false, 0];
    }

}