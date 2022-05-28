<?php

namespace App\Poker;

use App\Card\Card;

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
     * an array of one int and one int array
     * where the int represent the rule value
     * exampel: pair is 1, two-pair is 2
     * and the array represent is ints that
     * represent the highest value cards in the
     * hand used as tiebreakers.
     * exampel: if hand is two pair the two card
     * values of the two pair and the highest
     * remaining card is in the array
     * @return array<int, array>
     */
    public function CalculateHandValue(): array
    {
        $royal = $this->RoyalFlush();
        if ($royal) {
            return [9];
        }
        $straightF = $this->StraightFlush();
        if ($straightF[0]) {
            return [8, [$straightF[1]]];
        }
        $fourKind = $this->FourOfAKind();
        if ($fourKind[0]) {
            return [7, [$fourKind[1], $fourKind[2]]];
        }
        $fullHouse = $this->FullHouse();
        if ($fullHouse[0]) {
            return [6, [$fullHouse[1], $fullHouse[2]]];
        }
        $flush = $this->Flush();
        if ($flush[0]) {
            return [5, [$flush[1]]];
        }
        $straight = $this->Straight();
        if ($straight[0]) {
            return [4, [$straight[1]]];
        }
        $threeKind = $this->ThreeOfAKind();
        if ($threeKind[0]) {
            return [3, array_splice($threeKind, 1, 3)];
        }
        $twoPair = $this->TwoPair();
        if ($twoPair[0]) {
            return [2, array_splice($twoPair, 1, 3)];
        }
        $pair = $this->Pair();
        if ($pair[0]) {
            return [1, array_merge([$pair[1]], $pair[2])];
        }
        $highCards = $this->HighCards();
        return [0, $highCards];
    }

    /**
     * Returns true if hand is
     * royal-flush else false.
     */
    private function RoyalFlush(): bool
    {
        $colors = array_count_values($this->handColors);
        $colors = array_filter($colors, function($value) {
            return $value > 4;
        });

        if (count($colors) == 0) return false;

        $color = array_key_first($colors);
        $colorsNumberValue  = array_map(
            function (Card $card) use ($color) 
            {
                if ($card->getColorName() == $color) {
                    return $card->getNumberValue();
                }
        }, $this->hand);

        $royalNumbers = [14, 13, 12, 11, 10];

        if (count(array_intersect($royalNumbers, $colorsNumberValue)) == 5) {
            return true;
        }
        
        return false;
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

        for ($i = 0; $i < count($colorsNumberValue); $i++) {
            $cardNumber = $colorsNumberValue[$i];

            if ($prevCard == $cardNumber + 1 || $prevCard == 0) {
                if ($straightFlushValue == 4) {
                    return [true, $highCard];
                }
                if ($i == count($colorsNumberValue) - 1) {
                    $straightFlushValue += 1;
                } elseif ($cardNumber - 1 == $this->handNumbers[$i + 1]) {
                    $straightFlushValue += 1;
                    $highCard = ($prevCard == 0) ? $cardNumber : $highCard;
                    $prevCard = $cardNumber;
                } elseif ($cardNumber == $this->handNumbers[$i + 1]) {
                    $prevCard = $cardNumber + 1;
                } else {
                    $prevCard = 0;
                    $straightFlushValue = 0;
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
     * an four of a kind and an int that
     * is the card value of the fours,
     * 0 if bool is false.
     * and an int that is the card value
     * of the biggest remainings cards 
     * 0 if bool is false.
     * @return array<bool, int, int>
     */
    public function FourOfAKind(): array
    {
        $numbers = array_count_values($this->handNumbers);
        $numbers = array_filter($numbers, function($value) {
            return $value > 3;
        });

        if (count($numbers) == 0) return [false, 0];

        $highCard = 0;
        if (array_key_first($numbers) != $this->handNumbers[0]) {
            $highCard = $this->handNumbers[0];
        } else {
            $highCard = $this->handNumbers[4];
        }

        $fourHighCard = array_key_first($numbers);
        return [true, $fourHighCard, $highCard];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an full house and an int that
     * is the card value of the threes,
     * 0 if bool is false.
     * and an int that is the card value
     * of the twoos
     * 0 if bool is false.
     * @return array<bool, int, int>
     */
    public function FullHouse(): array
    {
        $localHandNumber = $this->handNumbers;
        $threes = array_count_values($localHandNumber);
        $threes = array_filter($threes, function($value) {
            return $value > 2;
        });

        if (count($threes) == 0) return [false, 0, 0];

        $threesKeys = array_keys($threes);
        rsort($threesKeys);
        $threesHighCard = $threesKeys[0];
        foreach (array_keys($localHandNumber, $threesHighCard) as $threesCard) {
            unset($localHandNumber[$threesCard]);
        }

        $twos = array_count_values($localHandNumber);
        $twos = array_filter($twos, function($value) {
            return $value > 1;
        });

        if (count($twos) == 0) return [false, 0, 0];


        $twosKeys = array_keys($twos);
        rsort($twosKeys);
        $twosHighCard = $twosKeys[0];

        return [true, $threesHighCard, $twosHighCard];
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

        for ($i = 0; $i <= count($this->handNumbers) - 1; $i++) {
            $cardNumber = $this->handNumbers[$i];

            if ($prevCard == $cardNumber + 1 || $prevCard == 0) {
                if ($straightValue == 4) {
                    return [true, $highCard];
                } if ($i == count($this->handNumbers) - 1) {
                    $straightValue += 1;
                } elseif ($cardNumber - 1 == $this->handNumbers[$i + 1]) {
                    $straightValue += 1;
                    $highCard = ($prevCard == 0) ? $cardNumber : $highCard;
                    $prevCard = $cardNumber;
                } elseif ($cardNumber == $this->handNumbers[$i + 1]) {
                    $prevCard = $cardNumber + 1;
                } else {
                    $prevCard = 0;
                    $straightValue = 0;
                }
            } else {
                $straightValue = 0;
                $prevCard = 0;
            }
        }
        return [false, 0];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * an three of a kind and an int that
     * is the card value of the threes,
     * and two ints that is the two biggest
     * remainings cards.
     * ints are 0 if bool is false.
     * @return array<bool, int, int, int>
     */
    private function ThreeOfAKind(): array
    {
        $numbers = array_count_values($this->handNumbers);
        $numbers = array_filter($numbers, function($value) {
            return $value > 2;
        });

        if (count($numbers) == 0) return [false, 0];

        $threeHighCard = array_key_first($numbers);

        $highCardNumbers = array_filter($this->handNumbers,
        function($value) use ($threeHighCard) {
            return $value != $threeHighCard;
        });
        $highCardKeys = array_keys($highCardNumbers);

        $firstHighCard = $highCardNumbers[$highCardKeys[0]];
        $secondHighCard = $highCardNumbers[$highCardKeys[1]];
        
        return [true, $threeHighCard, $firstHighCard, $secondHighCard];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * a two of a kind and an int that 
     * is high card of the bigger pair,
     * and an int that is the high card
     * of the smaller pair and an int that
     * is the biggest remaining card
     * ints are 0 if bool is false.
     * @return array<bool, int, int, int>
     */
    private function TwoPair(): array
    {
        $numbers = array_count_values($this->handNumbers);
        $numbers = array_filter($numbers, function($value) {
            return $value == 2;
        });

        if (count($numbers) < 2) return [false, 0];

        $numbersKeys = array_keys($numbers);
        rsort($numbersKeys);

        $bigHighCard = $numbersKeys[0];
        $smallHighCard = $numbersKeys[1];
    
        $highCardNumbers = array_filter($this->handNumbers,
        function($value) use ($bigHighCard, $smallHighCard) {
            return $value != $bigHighCard and $value != $smallHighCard;
        });
        $highCard = $highCardNumbers[array_key_first($highCardNumbers)];
        
        return [true, $bigHighCard, $smallHighCard, $highCard];
    }

    /**
     * Returns an array with an
     * bool that says if the hand is
     * a two of a kind and an int that 
     * is high card of the pair,
     * and an array of three ints that
     * is the biggest remaining cards.
     * ints are 0 if bool is false.
     * @return array<bool, int, array>
     */
    private function Pair(): array
    {
        $numbers = array_count_values($this->handNumbers);
        $numbers = array_filter($numbers, function($value) {
            return $value == 2;
        });

        if (count($numbers) == 0) return [false, 0];

        $pairHighCard = array_key_first($numbers);
    
        $highCardNumbers = array_filter($this->handNumbers,
        function($value) use ($pairHighCard) {
            return $value != $pairHighCard;
        });

        $highCards = [];
        $count = 0;
        foreach ($highCardNumbers as $highCard) {
            $highCards[] = $highCard;
            $count++;
            if ($count == 3) break;
        }
        
        return [true, $pairHighCard, $highCards];
    }

    /**
     * Returns an array ints of the five
     * highest cards.
     * @return int[]
     */
    private function HighCards(): array
    {
        $highCards = [];
        for ($i = 0; $i < 5; $i++) {
            $highCards[] = $this->handNumbers[$i];
        }
        
        return $highCards;
    }

}