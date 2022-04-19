<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    /**
     * @Route("/card/deck/shuffle", name="deck-shuffle")
     */
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new \App\Card\Deck();
        $deck->shuffle();
        
        $session->set("deck", $deck->getDeck());

        return $this->render('card/deck.html.twig', [
            'title' => "Blandad kortlek",
            'header' => "Här är alla kort i kortleken efter blandning",
            'deck' => $deck->getDeck()
        ]);
    }
}
