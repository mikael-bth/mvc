<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\Card;
use App\Card\Deck;
use App\Card\Player;
use App\Card\GamePoker;

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
        $game = new GamePoker;
        if (!$pokerGame) {
            $pokerGame = new PokerGame();
            $pokerGame->setComputerMoney(100);
            $pokerGame->setPlayerMoney(100);
            $pokerGame->setActiveGame(true);
            $entityManager->persist($pokerGame);
            $entityManager->flush();
            $session->set("poker-game", $game);
        } else {
            if (!$pokerGame->isActiveGame() or $newGame) {
                $pokerGame->setComputerMoney(100);
                $pokerGame->setPlayerMoney(100);
                $pokerGame->setActiveGame(true);
                $entityManager->flush();
                $session->set("poker-game", $game);
            }
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
            } elseif ($game->getState() > 1) {
                $deck = $game->drawCards($game->getTable(), 1, $deck);
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
            'table' => $game->getTable(),
            'pot' => $game->getPot(),
            'player' => $game->getPlayer(),
            'pMoney' => $pokerGame->getPlayerMoney(),
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

        $currentBet = 0;

        $game = $session->get("poker-game");
        $player = $game->getPlayer();
        $computer = $game->getComputer();
        $pot = $game->getPot();

        $entityManager = $doctrine->getManager();
        $pokerGame = $pokerRepository
            ->find(1);
        $pokerGameChange = $entityManager->getRepository(PokerGame::class)->find(1);
        $playerMoney = $pokerGame->getPlayerMoney();
        $computerMoney = $pokerGame->getComputerMoney();

        if ($call) {
            if ($computer->getBet() > 0) {
                $currentBet = $computer->getBet();
                $player->setBet($currentBet);
            } else {
                $currentBet = $this->baseBet;
                $player->setBet($currentBet);
            }
        } elseif ($bet) {
            $currentBet = intval($betAmount);
            $player->setBet($currentBet);
        } elseif ($all) {
            $currentBet = $playerMoney;
            $player->setBet($currentBet);
        } else {
            $game->setState(0);
            $game->setPState(-1);
            $session->set("poker-game", $game);

            $pokerGameChange->setComputerMoney($computerMoney + $pot);
            $entityManager->flush();
            
            return $this->redirectToRoute("project-game-play");
        }

        $pokerGameChange->setPlayerMoney($playerMoney - $currentBet);
        $entityManager->flush();

        $game->setPot($pot + $currentBet);
        
        $game->setState($game->getState() + 1);
        $session->set("poker-game", $game);
        return $this->redirectToRoute("project-game-play");
    }

}
