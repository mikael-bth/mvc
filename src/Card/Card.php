<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $color;
    protected $color_name;

    public function __construct($value, $color, $color_name)
    {
        $this->value = $value;
        $this->color = $color;
        $this->color_name = $color_name;
    }

    public function getAsString(): string
    {
        return "{$this->value} | {$this->color}";
    }

    public function getAsJSONString(): string
    {
        return "{$this->value} | {$this->color_name}";
    }

    public function getColorName(): string
    {
        return $this->color_name;
    }
}