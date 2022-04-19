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

    /**
     * @Route("/card/deck/draw", name="deck-draw")
     */
    public function draw(SessionInterface $session): Response
    {
        $deck = new \App\Card\Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);
        $cardCount = $deck->getDeckSize();
        if ($cardCount == 0) {
            return $this->render('card/draw.html.twig', [
                'title' => "Dra ett kort",
                'header' => "Slut på kort",
                'card' => "Inga kort kvar i kortleken",
                'color' => "spade",
                'count' => $cardCount
            ]);
        }
        
        $card = $deck->drawCard();
        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());

        return $this->render('card/draw.html.twig', [
            'title' => "Dra ett kort",
            'header' => "Du fick kortet...",
            'card' => $card->getAsString(),
            'color' => $card->getColorName(),
            'count' => $cardCount
        ]);
    }

    /**
     * @Route("/card/deck/draw/{numDraws}", name="deck-draw-count")
     */
    public function drawCount(int $numDraws, SessionInterface $session): Response
    {
        $deck = new \App\Card\Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);
        $cardCount = $deck->getDeckSize();
        if ($cardCount - $numDraws < 0) {
            return $this->render('card/draw.html.twig', [
                'title' => "Dra ett kort",
                'header' => "Inte tillräckligt med kort",
                'card' => "Inte tillräckligt med kort för att dra ${numDraws} kort",
                'color' => "spade",
                'count' => $cardCount
            ]);
        }
        
        $cardList = [];
        for ($i=0; $i < $numDraws; $i++) { 
            $card = $deck->drawCard();
            $cardList[] = $card;
        }
        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());

        return $this->render('card/draws.html.twig', [
            'title' => "Dra flera kort",
            'header' => "Du fick korten...",
            'cards' => $cardList,
            'count' => $cardCount
        ]);
    }
}
