<?php

namespace App\Card;

use App\Card\Player;

class Deal
{
    protected array $playerList;

    /**
     * Constructor
     */
    public function __construct(int $numPlayers, int $numCards, Deck $deck)
    {
        for ($i = 1; $i <= $numPlayers; $i++) {
            $player = new Player("Player ${i}");
            for ($y = 0; $y < $numCards; $y++) {
                $card = $deck->drawCard();
                $player->addCard($card);
            }
            $this->playerList[] = $player;
        }
    }

    /**
     * Returns the playerList
     * @return array<Player> $playerList
     */
    public function getPlayers(): array
    {
        return $this->playerList;
    }
}
