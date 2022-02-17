<?php

namespace Api\Controller;

use Api\Entity\Card;
use Api\Repository\CardRepository;
use Api\Controller\AbstractController;

class CardController extends AbstractController
{
    private $cardRepository;

    public function __construct()
    {
        $this->cardRepository = new CardRepository();
    }   

    public function getCard($id)
    {
        $card = $this->cardRepository->findOne($id);

        echo json_encode($card);
    }

    public function newCard($post) //enregistre une card dans la bdd
    {
        if ($post) {
            $card = new Card();
            $card
                ->setTitle($post["title"])
                ->setContent($post["content"])
                ->setOrderNb($post["orderNb"])
                ->setColor($post["color"])
                ->setListe_id($post["liste_id"]);

            $this->cardRepository->addCard($card);
            $lastCard =$this->cardRepository->getLastCard();
            echo json_encode($lastCard);
        }
    }

    public function editCard($post)
    {
            $card = $this->cardRepository->findOne($post['cardId']);
            $card
                ->setTitle($post["title"])
                ->setContent($post["content"])
                ->setOrderNb($post["orderNb"])
                ->setColor($post["color"])
                ->setListe_id($post["liste_id"]);

            $this->cardRepository->editCard($card);
            $updatedCard = $this->cardRepository->findOne($post['cardId']);
            echo json_encode($updatedCard);
    }

    public function deleteCard($id)
    {
        echo json_encode($this->cardRepository->delete($id));
    }

    public function deleteAllCardsByListId($id)
    {
        echo json_encode($this->cardRepository->removeAllCardsByListId($id));
    } 
}

