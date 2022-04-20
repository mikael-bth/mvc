<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
                'title' => "Dra flera kort",
                'header' => "Inte tillräckligt med kort",
                'card' => "Inte tillräckligt för att dra ${numDraws} kort",
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

    /**
     * @Route("/card/deck/deal/{numPlayers}/{numCards}", name="deck-deal")
     */
    public function deal(int $numPlayers, int $numCards, SessionInterface $session): Response
    {
        $deck = new \App\Card\Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);

        $cardCount = $deck->getDeckSize();
        $totalCards = $numCards * $numPlayers;
        if ($cardCount - $totalCards < 0) {
            return $this->render('card/draw.html.twig', [
                'title' => "Dela ut kort",
                'header' => "Inte tillräckligt med kort",
                'card' => "Inte tillräckligt för att dra ${totalCards} kort",
                'color' => "spade",
                'count' => $cardCount
            ]);
        }

        $playerList = [];
        for ($i=1; $i <= $numPlayers; $i++)
        {
            $player = new \App\Card\Player("Player ${i}");
            for ($y=0; $y < $numCards; $y++) 
            {
                $card = $deck->drawCard();
                $player->addCard($card);
            }
            $playerList[] = $player;
        }

        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());

        return $this->render('card/deal.html.twig', [
            'title' => "Dela ut kort",
            'header' => "Spelarna fick korten...",
            'players' => $playerList,
            'count' => $cardCount
        ]);
    }

    /**
     * @Route("/card/deck2", name="deck2")
     */
    public function deck2(): Response
    {
        $deck = new \App\Card\Deck2Jokers();

        return $this->render('card/deck.html.twig', [
            'title' => "Kortlek 2",
            'header' => "Här är alla kort i kortleken",
            'deck' => $deck->getDeck()
        ]);
    }
}
