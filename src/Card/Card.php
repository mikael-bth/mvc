<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $color;
    private $color_list = ["Hearts", "Spades", "Clubs", "Diamonds"];

    public function __construct()
    {
        $this->value = random_int(1, 13);
        $this->color = $this->color_list[random_int(0, 4)];
    }

    public function roll(): int
    {
        $this->value = random_int(1, 13);
        $this->color = $this->color_list[random_int(0, 4)];
        return $this->value;
    }

    public function getAsString(): string
    {
        return "{$this->value} | {$this->color}";
    }
}