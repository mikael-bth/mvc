<?php

namespace App\Entity;

use App\Repository\PokerGameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokerGameRepository::class)]
class PokerGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $ComputerMoney;

    #[ORM\Column(type: 'integer')]
    private $PlayerMoney;

    #[ORM\Column(type: 'boolean')]
    private $ActiveGame;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComputerMoney(): ?int
    {
        return $this->ComputerMoney;
    }

    public function setComputerMoney(int $ComputerMoney): self
    {
        $this->ComputerMoney = $ComputerMoney;

        return $this;
    }

    public function getPlayerMoney(): ?int
    {
        return $this->PlayerMoney;
    }

    public function setPlayerMoney(int $PlayerMoney): self
    {
        $this->PlayerMoney = $PlayerMoney;

        return $this;
    }

    public function isActiveGame(): ?bool
    {
        return $this->ActiveGame;
    }

    public function setActiveGame(bool $ActiveGame): self
    {
        $this->ActiveGame = $ActiveGame;

        return $this;
    }
}
