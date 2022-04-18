<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $color;

    public function __construct($value, $color)
    {
        $this->value = $value;
        $this->color = $color;
    }

    public function getAsString(): string
    {
        return "{$this->value} | {$this->color}";
    }
}