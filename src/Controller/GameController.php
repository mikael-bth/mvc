<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use \App\Card\Deck;
use \App\Card\Player;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function home(): Response
    {
        return $this->render('game.html.twig', [
            'title' => "Kortspelet 21",
            'header' => "Här kan du spela kortspelet 21",
        ]);
    }

    /**
     * @Route("/game/doc", name="game-doc")
     */
    public function documentation(): Response
    {
        return $this->render('game/doc.html.twig', [
            'title' => "21 dokumentation",
            'header' => "Dokumentation om hur jag implementerade kortspelet 21 på sidan",
        ]);
    }
}
