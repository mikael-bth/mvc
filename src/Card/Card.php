<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $valueNumber;
    protected $color;
    protected $colorName;

    public function __construct($value, $valueNumber, $color, $colorName)
    {
        $this->value = $value;
        $this->valueNumber = $valueNumber;
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

    public function getNumberValue(): string
    {
        return $this->valueNumber;
    }

    public function setNumberValue(int $newValue): void
    {
        $this->valueNumber = $newValue;
    }
}
