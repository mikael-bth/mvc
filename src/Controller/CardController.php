<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Deck;
use App\Card\Deck2Jokers;
use App\Card\Deal;

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
     * @Route("/card/reset", name="reset")
     */
    public function reset(SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute("card");
    }

    /**
     * @Route("/card/deck", name="deck")
     */
    public function deck(): Response
    {
        $deck = new Deck();

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
        $deck = new Deck();
        $deck->shuffleDeck();
        $session->invalidate();
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
        $deck = new Deck();
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
        $deck = new Deck();
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

        $cardList = $deck->drawCards($numDraws);
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
        $deck = new Deck();
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

        $deal = new Deal($numPlayers, $numCards, $deck);
        $playerList = $deal->getPlayers();

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
        $deck = new Deck2Jokers();

        return $this->render('card/deck.html.twig', [
            'title' => "Kortlek 2",
            'header' => "Här är alla kort i kortleken",
            'deck' => $deck->getDeck()
        ]);
    }
}
