<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use \App\Card\Deck;
use App\Card\Game;

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

    /**
     * @Route("/game/play/{status}", name="game-play")
     */
    public function game(int $status, SessionInterface $session): Response
    {
        $deck = new Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);

        $game = $session->get("game") ?? new Game();

        if ($status == 1) {
            if ($deck->getDeckSize() < 12) {
                $deck = new Deck();
                $deck->shuffleDeck();
            }
            $session->remove("game");
            $game = new Game();
            $deck = $game->drawPlayer($deck);
        }

        $result = false;
        $message = '';
        $standing = $game->getPlayerStatus();

        if ($game->getPlayerSum() > 21) {
            $result = true;
            $message = "Banken vann, Du gick över 21";
            $standing = true;
        } else if ($standing and $game->getPlayerSum() <= 21) {
            $deck = $game->drawBank($deck);
            $result = true;
            if ($game->getBankSum() > 21) {
                $message = "Du vann, Banken gick över 21";
            } else if ($game->getBankSum() > $game->getPlayerSum()) {
                $message = "Banken vann, Banken var närmare 21 än dig";
            } else {
                $message = "Du vann, du var närmare 21 än banken";
            }
        }

        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());
        $session->set("game", $game);

        return $this->render('game/game.html.twig', [
            'title' => "Spela kortspelet 21",
            'header' => "Kortspelet 21",
            'bank' => $game->getBank(),
            'bankSum' => $game->getBankSum(),
            'player' => $game->getPlayer(),
            'playerSum' => $game->getPlayerSum(),
            'standing' => $standing,
            'result' => $result,
            'message' => $message,
            'count' => $cardCount
        ]);
    }

    /**
     * @Route(
     *      "/game/processing",
     *      name="game-play-process",
     *      methods={"POST"}
     * )
     */
    public function gameProcess(Request $request, SessionInterface $session): Response
    {
        $game = $session->get("game") ?? new Game();

        $deck = new Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);

        $stand  = $request->request->get('stand');
        $draw  = $request->request->get('draw');

        if ($stand){
            $game->playerStay();
        } else if ($draw) {
            $deck = $game->drawPlayer($deck);
        }

        $session->set("deck", $deck->getDeck());
        $session->set("game", $game);

        return $this->redirectToRoute("game-play", array('status' => 0));
    }

    /**
     * @Route(
     *      "/game/result/processing",
     *      name="game-result-process",
     *      methods={"POST"}
     * )
     */
    public function resultProcess(Request $request, SessionInterface $session): Response
    {
        $exit  = $request->request->get('exit');
        $restart  = $request->request->get('restart');

        if ($exit){
            return $this->redirectToRoute("game");
        } else if ($restart) {
            return $this->redirectToRoute("game-play", array('status' => 1));
        }
        return $this->redirectToRoute("game-play", array('status' => 0));
    }
}
