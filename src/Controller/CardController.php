<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card", name="card")
     */
    public function home(): Response
    {
        return $this->render('card.html.twig', [
            'title' => "Kortspel",
            'header' => "Länkar till kort sidorna",
        ]);
    }

    /**
     * @Route("/card/deck", name="deck")
     */
    public function deck(): Response
    {
        $deck = new \App\Card\Deck();

        return $this->render('card/deck.html.twig', [
            'title' => "Kortlek",
            'header' => "Här är alla kort i kortleken",
            'deck' => $deck->getDeck()
        ]);
    }
}
