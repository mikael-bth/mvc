<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Deck;
use App\Poker\GamePoker;
use App\Poker\PokerComputer;
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
     * @Route("/proj/reset", name="project-reset")
     */
    public function resetDB(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $pokerGame = $entityManager->getRepository(PokerGame::class)->find(1);
        if ($pokerGame) {
            $pokerGame->setActiveGame(false);
            $entityManager->flush();
        }
        return $this->redirectToRoute("project-about");
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
            $newPokerGame->setComputerMoney(200);
            $newPokerGame->setPlayerMoney(200);
            $newPokerGame->setComputerBet(0);
            $newPokerGame->setPlayerBet(0);
            $newPokerGame->setPot(0);
            $newPokerGame->setActiveGame(true);
            $entityManager->persist($newPokerGame);
            $entityManager->flush();
            $session->set("poker-game", $game);
            return $this->redirectToRoute("project-game-play");
        }

        if (!$pokerGame->isActiveGame() or $newGame) {
            $pokerGame->setComputerMoney(200);
            $pokerGame->setPlayerMoney(200);
            $pokerGame->setComputerBet(0);
            $pokerGame->setPlayerBet(0);
            $pokerGame->setPot(0);
            $pokerGame->setActiveGame(true);
            $entityManager->flush();
            $session->set("poker-game", $game);
        }

        return $this->redirectToRoute("project-game-play");
    }

    /**
     * @Route(
     *      "/proj/game/result/process",
     *      name="project-game-result-process",
     *      methods={"POST"}
     * )
     */
    public function gameResultProcess(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $pokerGameUpdate = $entityManager->getRepository(PokerGame::class)->find(1);
        $pokerGameUpdate->setActiveGame(false);
        $entityManager->flush();

        $exit = $request->request->get("exit");

        if ($exit) {
            return $this->redirectToRoute("project-home");
        }

        return $this->redirectToRoute("project-game-start-process", [
            'request' => $request
        ], 307);
    }


    /**
     * @Route("/proj/game/play", name="project-game-play")
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function game(
        PokerGameRepository $pokerRepository,
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $deck = new Deck();
        $cardList = $session->get("deck") ?? $deck->getDeck();
        $deck->setDeck($cardList);
        $deck->shuffleOnLow(9);

        $game = $session->get("poker-game");
        $pokerGame = $pokerRepository
            ->find(1);

        $showCards = false;
        $result = false;
        $roundDone = false;

        if ($game->getState() != $game->getPState()) {
            switch ($game->getState()) {
                case 0:
                    $deck = $game->drawCards($game->getPlayer(), 2, $deck);
                    $deck = $game->drawCards($game->getComputer(), 2, $deck);
                    break;
                case 1:
                    $deck = $game->drawCards($game->getTable(), 3, $deck);
                    break;
                case 4:
                    $countTableCards = count($game->getTable()->getHand());
                    $deck = $game->drawCards($game->getTable(), 5 - $countTableCards, $deck);

                    $showCards = true;

                    $winner = $game->getResults($game->getPlayer(), $game->getComputer(), $game->getTable());
                    $entityManager = $doctrine->getManager();
                    $pokerGameUpdate = $entityManager->getRepository(PokerGame::class)->find(1);

                    $playerMoney = $pokerGame->getPlayerMoney();
                    $computerMoney = $pokerGame->getComputerMoney();
                    $activePot = $pokerGame->getPot();

                    $newPlayerMoney = $playerMoney + $activePot / 2;
                    $newComputerMoney = $computerMoney + $activePot / 2;
                    $game->setMessage("Tie, pot is shared");

                    if ($winner === 0) {
                        $newPlayerMoney = $playerMoney + $activePot;
                        $newComputerMoney = $computerMoney;
                        $game->setMessage("You won the pot");
                    } elseif ($winner === 1) {
                        $newComputerMoney = $computerMoney + $activePot;
                        $newPlayerMoney = $playerMoney;
                        $game->setMessage("Dator won the pot");
                    }

                    $pokerGameUpdate->setPlayerMoney($newPlayerMoney);
                    $pokerGameUpdate->setComputerMoney($newComputerMoney);

                    if ($newComputerMoney === 0) {
                        $game->setMessage("Dator is out of money. You win");
                        $result = true;
                    } elseif ($newPlayerMoney === 0) {
                        $game->setMessage("You are out of money. Dator wins");
                        $result = true;
                    }

                    $pokerGameUpdate->setPlayerBet(0);
                    $pokerGameUpdate->setComputerBet(0);
                    $pokerGameUpdate->setPot(0);
                    $entityManager->flush();

                    $roundDone = (!$result) ? true : false;
                    break;
                default:
                    $deck = $game->drawCards($game->getTable(), 1, $deck);
                    break;
            }
        }

        if ($game->getState() < 4) {
            $game->setPState($game->getState());
        }

        $message = $game->getMessage();

        $cardCount = $deck->getDeckSize();
        $session->set("deck", $deck->getDeck());
        $session->set("poker-game", $game);

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
            'count' => $cardCount,
            'message' => $message,
            'result' => $result,
            'roundDone' => $roundDone,
        ]);
    }

    /**
     * @Route(
     *      "/proj/game/play/new/round",
     *      name="project-game-new-round",
     *      methods={"POST"}
     * )
     */
    public function newRound(
        SessionInterface $session
    ): Response {
        $game = new GamePoker();
        $session->set("poker-game", $game);
        return $this->redirectToRoute("project-game-play");
    }

    /**
     * @Route(
     *      "/proj/game/play/process",
     *      name="project-game-play-process",
     *      methods={"POST"}
     * )
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
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
        $fold = $request->request->get('fold');
        $betAmount = $request->request->get('betAmount');

        $game = $session->get("poker-game");

        $entityManager = $doctrine->getManager();
        $pokerGameUpdate = $entityManager->getRepository(PokerGame::class)->find(1);

        $pokerGame = $pokerRepository
            ->find(1);
        $playerMoney = $pokerGame->getPlayerMoney();
        $playerBet = $pokerGame->getPlayerBet();
        $computerMoney = $pokerGame->getComputerMoney();
        $computerBet = $pokerGame->getComputerBet();
        $activePot = $pokerGame->getPot();

        if ($game->getState() == 0 && $playerBet === 0) {
            $session->remove("p-computer");
        }

        $pokerComputer = $session->get("p-computer") ?? new PokerComputer($game->getComputer()->getHand());

        $playerNewBet = 0;

        if ($call) {
            $playerNewBet = ($computerBet > 0) ? $computerBet - $playerBet : $this->baseBet;
        } elseif ($bet) {
            if ($computerBet - $playerBet > intval($betAmount)) {
                $game->setMessage("Too small bet");
                return $this->redirectToRoute("project-game-play");
            }
            if (intval($betAmount) > $playerMoney) {
                $game->setMessage("Not enough money");
                return $this->redirectToRoute("project-game-play");
            }
            $playerNewBet = intval($betAmount);
        } elseif ($all) {
            $playerNewBet = $playerMoney;
        } elseif ($fold) {
            $game = new GamePoker();
            $game->setMessage("Player fold");
            $session->set("poker-game", $game);

            $pokerGameUpdate->setComputerBet(0);
            $pokerGameUpdate->setPlayerBet(0);
            $pokerGameUpdate->setPot(0);
            $pokerGameUpdate->setComputerMoney($computerMoney + $activePot);
            $entityManager->flush();

            return $this->redirectToRoute("project-game-play");
        }

        if ($playerBet + $playerNewBet == $computerBet && $playerNewBet < $playerMoney) {
            if ($computerMoney === 0) {
                $pokerGameUpdate->setPlayerBet($playerBet + $playerNewBet);
                $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet);
                $pokerGameUpdate->setPot($activePot + $playerNewBet);
                $entityManager->flush();

                $game->setMessage("Player call");
                $game->setState(4);
                $session->set("poker-game", $game);

                return $this->redirectToRoute("project-game-play");
            }
            $pokerGameUpdate->setPlayerBet(0);
            $pokerGameUpdate->setComputerBet(0);
            $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet);
            $pokerGameUpdate->setPot($activePot + $playerNewBet);
            $entityManager->flush();

            $game->setMessage("Player call");
            $game->setState($game->getState() + 1);
            $session->set("poker-game", $game);

            return $this->redirectToRoute("project-game-play");
        }

        $computerNewBet = 0;

        $computerAction = $pokerComputer->getAction($game->getState());
        error_log($computerAction);
        switch ($computerAction) {
            case 0:
                $computerNewBet = ($playerBet + $playerNewBet) - $computerBet;
                $game->setMessage("Dator call");
                break;
            case 1:
                $computerNewBet = $pokerComputer->getBetAmount($playerBet + $playerNewBet, $computerMoney);
                $game->setMessage("Dator bet");
                if ($computerNewBet == $computerMoney) {
                    $game->setMessage("Dator all in");
                } elseif ($computerNewBet == $playerNewBet) {
                    $game->setMessage("Dator call");
                }
                break;
            case 2:
                $game = new GamePoker();
                $game->setMessage("Dator fold");
                $session->set("poker-game", $game);

                $pokerGameUpdate->setComputerBet(0);
                $pokerGameUpdate->setPlayerBet(0);
                $pokerGameUpdate->setPot(0);
                $pokerGameUpdate->setPlayerMoney($playerMoney + $activePot);
                $entityManager->flush();

                return $this->redirectToRoute("project-game-play");
        }

        if ($playerNewBet >= $playerMoney) {
            $playerNewBet = $playerMoney;
            $pokerGameUpdate->setPlayerBet($playerBet + $playerNewBet);
            $pokerGameUpdate->setPlayerMoney(0);
            $betDiff = ($computerBet + $computerNewBet) - ($playerBet + $playerNewBet);
            $pokerGameUpdate->setComputerBet($computerBet + $computerNewBet - $betDiff);
            $pokerGameUpdate->setComputerMoney($computerMoney - $computerNewBet + $betDiff);
            $pokerGameUpdate->setPot($activePot + $playerNewBet + $computerNewBet - $betDiff);
            $entityManager->flush();

            $game->setMessage("Player all in");
            $game->setState(4);
            $session->set("poker-game", $game);

            return $this->redirectToRoute("project-game-play");
        }

        if ($playerNewBet > $computerMoney) {
            $pokerGameUpdate->setComputerBet($computerBet + $computerNewBet);
            $pokerGameUpdate->setComputerMoney(0);
            $betDiff = ($playerBet + $playerNewBet) - ($computerBet + $computerNewBet);
            $pokerGameUpdate->setPlayerBet($playerBet + $playerNewBet - $betDiff);
            $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet + $betDiff);
            $pokerGameUpdate->setPot($activePot + $computerNewBet + $playerNewBet - $betDiff);
            $entityManager->flush();

            $game->setState(4);
            $session->set("poker-game", $game);

            return $this->redirectToRoute("project-game-play");
        }


        if ($playerBet + $playerNewBet == $computerBet + $computerNewBet) {
            if ($computerNewBet == $computerMoney) {
                $pokerGameUpdate->setComputerBet($computerBet + $computerNewBet);
                $pokerGameUpdate->setPlayerBet($playerBet + $playerNewBet);
                $pokerGameUpdate->setComputerMoney($computerMoney - $computerNewBet);
                $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet);
                $pokerGameUpdate->setPot($activePot + $playerNewBet + $computerNewBet);
                $entityManager->flush();

                $game->setState(4);
                $session->set("poker-game", $game);

                return $this->redirectToRoute("project-game-play");
            }
            $pokerGameUpdate->setPlayerBet(0);
            $pokerGameUpdate->setComputerBet(0);
            $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet);
            $pokerGameUpdate->setComputerMoney($computerMoney - $computerNewBet);
            $pokerGameUpdate->setPot($activePot + $playerNewBet + $computerNewBet);
            $entityManager->flush();

            $game->setState($game->getState() + 1);
            $session->set("poker-game", $game);

            return $this->redirectToRoute("project-game-play");
        }

        $pokerGameUpdate->setPlayerBet($playerBet + $playerNewBet);
        $pokerGameUpdate->setComputerBet($computerBet + $computerNewBet);
        $pokerGameUpdate->setPlayerMoney($playerMoney - $playerNewBet);
        $pokerGameUpdate->setComputerMoney($computerMoney - $computerNewBet);
        $pokerGameUpdate->setPot($activePot + $playerNewBet + $computerNewBet);
        $entityManager->flush();

        return $this->redirectToRoute("project-game-play");
    }
}
