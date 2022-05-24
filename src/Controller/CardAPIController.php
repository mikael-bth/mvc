<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Deck;

class CardAPIController extends AbstractController
{
    /**
     * @Route("/card/api/deck", name="deck-api")
     */
    public function deck(): Response
    {
        $deck = new Deck();

        $cardList = [];
        foreach ($deck->getDeck() as $card) {
            $cardList[] = $card->getAsJSONString();
        }
        $data = [
            'deck' => $cardList
        ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
