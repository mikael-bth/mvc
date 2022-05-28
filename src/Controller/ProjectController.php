<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\Deck;
use App\Poker\GamePoker;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PokerGame;
use App\Repository\PokerGameRepository;

class ProjectController extends AbstractController
{
    private int $baseBet = 5;

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
        Request $request,
        SessionInterface $session
    ): Response {

        $entityManager = $doctrine->getManager();
        $pokerGame = $entityManager->getRepository(PokerGame::class)->find(1);

        $newGame = $request->request->get('new');
        $game = new GamePoker();

        if (!$pokerGame) {
            $newPokerGame = new PokerGame();
            $newPokerGame->setComputerMoney(100);
            $newPokerGame->setPlayerMoney(100);
            $newPokerGame->setComputerBet(0);
            $newPokerGame->setPlayerBet(0);
            $newPokerGame->setPot(0);
            $newPokerGame->setActiveGame(true);
            $entityManager->persist($newPokerGame);
            $entityManager->flush();
            $session->set("poker-game", $game);
        } else {
            if (!$pokerGame->isActiveGame() or $newGame) {
                $pokerGame->setComputerMoney(100);
                $pokerGame->setPlayerMoney(100);
                $pokerGame->setComputerBet(0);
                $pokerGame->setPlayerBet(0);
                $pokerGame->setPot(0);
                $pokerGame->setActiveGame(true);
                $entityManager->flush();
                $session->set("poker-game", $game);
            }
        }

        $pokerGame = $entityManager->getRepository(PokerGame::class)->find(1);
        if (!$pokerGame) {
            throw $this->createNotFoundException(
                'poker game not created'
            );
        }
        
        return $this->redirectToRoute("project-game-play");
    }

    /**
     * @Route("/proj/game/play", name="project-game-play")
     */
    public function game(
        PokerGameRepository $pokerRepository,
        SessionInterface $session
    ): Response {
        $deck = new Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);
        $deck->shuffleOnLow(9);

        $game = $session->get("poker-game");
        $pokerGame = $pokerRepository
            ->find(1);

        $showCards = false;
        
        if ($game->getState() != $game->getPState()) {
            if ($game->getState() == 0) {
                $deck = $game->drawCards($game->getPlayer(), 2, $deck);
                $deck = $game->drawCards($game->getComputer(), 2, $deck);
            } elseif ($game->getState() == 1) {
                $deck = $game->drawCards($game->getTable(), 3, $deck);
            } elseif ($game->getState() > 1 and $game->getState() < 4) {
                $deck = $game->drawCards($game->getTable(), 1, $deck);
            } else {
                
            }
        }
        $game->setPState($game->getState());

        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());

        return $this->render('project/game.html.twig', [
            'title' => "Poker",
            'header' => "Poker",
            'showCards' => $showCards,
            'computer' => $game->getComputer(),
            'cMoney' => $pokerGame->getComputerMoney(),
            'cBet' => $pokerGame->getComputerBet(),
            'table' => $game->getTable(),
            'pot' => $pokerGame->getPot(),
            'player' => $game->getPlayer(),
            'pMoney' => $pokerGame->getPlayerMoney(),
            'pBet' => $pokerGame->getPlayerBet(),
            'count' => $cardCount
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
        PokerGameRepository $pokerRepository,
        Request $request,
        SessionInterface $session
    ): Response {
        $call = $request->request->get('call');
        $bet = $request->request->get('bet');
        $all = $request->request->get('all');
        $betAmount = $request->request->get('betAmount');

        $playerBet = 0;
        $computerBet = $this->baseBet;

        $game = $session->get("poker-game");

        $entityManager = $doctrine->getManager();
        $pokerGameUpdate = $entityManager->getRepository(PokerGame::class)->find(1);

        $pokerGame = $pokerRepository
            ->find(1);
        $playerMoney = $pokerGame->getPlayerMoney();
        $computerMoney = $pokerGame->getComputerMoney();
        $activePot = $pokerGame->getPot();

        if ($call) {
            $playerBet = $computerBet;
        } elseif ($bet) {
            $playerBet = intval($betAmount);
            $computerBet = $playerBet;
        } elseif ($all) {
            $game->setState(0);
            $game->setPState(-1);
            $session->set("poker-game", $game);

            $pokerGameUpdate->setComputerBet(0);
            $pokerGameUpdate->setPlayerBet(0);
            $pokerGameUpdate->setPot(0);
            $pokerGameUpdate->setPlayerMoney($playerMoney + $activePot);
            $entityManager->flush();

            return $this->redirectToRoute("project-game-play");
        } else {
            $game = new GamePoker();
            $session->set("poker-game", $game);

            $pokerGameUpdate->setComputerBet(0);
            $pokerGameUpdate->setPlayerBet(0);
            $pokerGameUpdate->setPot(0);
            $pokerGameUpdate->setComputerMoney($computerMoney + $activePot);
            $entityManager->flush();

            return $this->redirectToRoute("project-game-play");
        }

        $pokerGameUpdate->setPlayerBet($playerBet);
        $pokerGameUpdate->setComputerBet($computerBet);
        $pokerGameUpdate->setPlayerMoney($playerMoney - $playerBet);
        $pokerGameUpdate->setComputerMoney($computerMoney - $computerBet);
        $pokerGameUpdate->setPot($activePot + $playerBet + $computerBet);
        $entityManager->flush();

        $playerBet = $pokerGame->getPlayerBet();
        $computerBet = $pokerGame->getComputerBet();

        if ($playerBet == $computerBet) {
            $game->setState($game->getState() + 1);
            $session->set("poker-game", $game);
        }
        
        return $this->redirectToRoute("project-game-play");
    }

}
