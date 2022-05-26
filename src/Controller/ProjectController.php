<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PokerGame;
use App\Repository\PokerGameRepository;

class ProjectController extends AbstractController
{
    /**
     * @Route("/proj", name="project-home")
     */
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'title' => "Projekt",
            'header' => "KMOM10 Projekt",
        ]);
    }

    /**
     * @Route("/proj/about", name="project-about")
     */
    public function about(): Response
    {
        return $this->render('project/about.html.twig', [
            'title' => "Om projektet",
            'header' => "Om projektet",
        ]);
    }

    /**
     * @Route("/proj/game/start", name="project-game-start")
     */
    public function gameStart(
        PokerGameRepository $pokerRepository
    ): Response {
        $pokerGame = $pokerRepository
            ->find(1);
        $active = false;
        if ($pokerGame) {
            $active = $pokerGame->isActiveGame();
        }
        return $this->render('project/game-start.html.twig', [
            'title' => "Spela poker",
            'header' => "Spela poker",
            'active' => $active
        ]);
    }

    /**
     * @Route(
     *      "/proj/game/start/process",
     *      name="project-game-start-process",
     *      methods={"POST"}
     * )
     */
    public function gameStartProcess(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $pokerGame = $entityManager->getRepository(PokerGame::class)->find(1);
        $newGame = $request->request->get('new');
        if (!$pokerGame) {
            $pokerGame = new PokerGame();
            $pokerGame->setComputerMoney(100);
            $pokerGame->setPlayerMoney(100);
            $pokerGame->setActiveGame(true);
            $entityManager->persist($pokerGame);
            $entityManager->flush();
        } else {
            if (!$pokerGame->isActiveGame() or $newGame) {
                $pokerGame->setComputerMoney(100);
                $pokerGame->setPlayerMoney(100);
                $pokerGame->setActiveGame(true);
                $entityManager->flush();
            }
        }
        
        return $this->redirectToRoute("project-game-play");
    }

    /**
     * @Route("/proj/game/play", name="project-game-play")
     */
    public function game(
        PokerGameRepository $pokerRepository
    ): Response {
        $card = new Card("A", 14, '♥', 'heart');
        $card2 = [$card, $card];
        $card3 = [$card, $card, $card];
        $computer = new Player("Dator");
        $table = new Player("Table");
        $player = new Player("Player");
        $computer->addCards($card2);
        $player->addCards($card2);
        $table->addCards($card3);
        $count = 52;
        return $this->render('project/game.html.twig', [
            'title' => "Poker",
            'header' => "Poker",
            'showCards' => false,
            'computer' => $computer,
            'table' => $table,
            'player' => $player,
            'count' => $count
        ]);
    }

    /**
     * @Route(
     *      "/proj/game/play/process",
     *      name="project-game-play-process",
     *      methods={"POST"}
     * )
     */
    public function gamePlayProcess(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $bet = $request->request->get('bet');
        $betAmount = $request->request->get('betAmount');
        error_log($betAmount);
        return $this->redirectToRoute("project-game-play");
    }

}
