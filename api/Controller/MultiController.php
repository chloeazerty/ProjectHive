<?php

namespace Api\Controller;

use Api\Repository\BoardRepository;
use Api\Repository\ListeRepository;
use Api\Repository\CardRepository;

use Api\Controller\AbstractController;

class MultiController extends AbstractController
{
    private $boardRepository;
    private $listeRepository;
    private $cardRepository;

    public function __construct() {
        $this->boardRepository = new BoardRepository();
        $this->listeRepository = new listeRepository();
        $this->cardRepository = new CardRepository();
    }

    public function getAllOfBoard($boardId, $userId) {
        $board = $this->boardRepository->findOne($boardId);
        if($board->getOwner_id() == $userId) {
            $listes = $this->listeRepository->getListesByBoard($boardId);
            $board->setListes($listes);
            foreach($listes as $liste) {
                $cards = $this->cardRepository->getCardsByListe($liste->getListeId());
                $liste->setCards($cards);
            }
            echo json_encode($board);
        } else {
            echo "Cette ressource est inaccessible.";
        }
    }
}