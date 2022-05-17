<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $valueNumber;
    protected $color;
    protected $colorName;

    /**
     * Constructor
     */
    public function __construct($value, $valueNumber, $color, $colorName)
    {
        $this->value = $value;
        $this->valueNumber = $valueNumber;
        $this->color = $color;
        $this->colorName = $colorName;
    }

    /**
     * Returns value and color of card as string.
     */
    public function getAsString(): string
    {
        return "{$this->value} | {$this->color}";
    }

    /**
     * Returns value and color of card as string,
     * the color value is though adapted to be used with json.
     */
    public function getAsJSONString(): string
    {
        return "{$this->value} | {$this->colorName}";
    }

    /**
     * Returns the color name value.
     */
    public function getColorName(): string
    {
        return $this->colorName;
    }

    /**
     * Returns the numerical value of the cards value.
     */
    public function getNumberValue(): string
    {
        return $this->valueNumber;
    }

    /**
     * Changes the the numerical value of the cards value.
     */
    public function setNumberValue(int $newValue): void
    {
        $this->valueNumber = $newValue;
    }
}
