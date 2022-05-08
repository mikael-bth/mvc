<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $color;
    protected $colorName;

    public function __construct($value, $color, $colorName)
    {
        $this->value = $value;
        $this->color = $color;
        $this->colorName = $colorName;
    }

    public function getAsString(): string
    {
        return "{$this->value} | {$this->color}";
    }

    public function getAsJSONString(): string
    {
        return "{$this->value} | {$this->colorName}";
    }

    public function getColorName(): string
    {
        return $this->colorName;
    }
}