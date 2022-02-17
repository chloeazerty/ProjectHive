<?php

namespace Api\Repository;

use Api\Entity\Card;

class CardRepository extends ManagerRepository
{
    public function addCard(object $card)
    {
        $sql = 'INSERT INTO card (title, content, orderNb, color, liste_id, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, NOW(), NOW())';
        $this->createQuery($sql, [
            $card->getTitle(),
            $card->getContent(),
            $card->getOrderNb(),
            $card->getColor(),
            $card->getListe_id()
        ]);
    }

    public function editCard($card)
    {
        $sql = "UPDATE card SET title = ?,  content = ?, orderNb = ?, color = ?, liste_id = ?, updatedAt = ? WHERE cardId = ?";
        $this->createQuery($sql, [
            $card->getTitle(),
            $card->getContent(),
            $card->getOrderNb(),
            $card->getColor(),
            $card->getListe_id(),
            date("Y-m-d H:i:s"),
            $card->getCardId()
        ]);
    }

    public function getCardsByListe(int $id) {
        $sql = 'SELECT * FROM card WHERE liste_id = ? ORDER BY orderNb ASC';
        $result = $this->createQuery($sql, [$id]);
        $cards = [];

        foreach($result as $row){
            $card = $this->buildObject($row);
            array_push($cards, $card);
        }

        return $cards;
    }

    public function getLastCard(){
        $sql = 'SELECT * FROM card WHERE cardId = (SELECT MAX(cardId) FROM card)';
        $result = $this->createQuery($sql);
        $row = $result->fetch();
        $lastCard = $this->buildObject($row);

        return $lastCard;
    }

    public function removeAllCardsByListId(int $id) {
        $sql = "DELETE FROM card WHERE liste_id = ?";
        $this->createQuery($sql, [$id]);
    }
}