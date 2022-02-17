<?php

namespace Api\Repository;

use Api\Entity\Liste;
use Api\Repository\ManagerRepository;


class ListeRepository extends ManagerRepository
{
    public function addListe(object $liste)
    {
        $sql = 'INSERT INTO liste (title, orderNb, board_id, posLeft, posTop, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?,NOW(), NOW())';
        $this->createQuery($sql, [
            $liste->getTitle(),
            $liste->getOrderNb(),
            $liste->getBoard_id(), //peut-être à revoir
            70,
            20
        ]);
    }

    public function editListe($liste)
    {
        $sql = "UPDATE liste SET title = ?,  orderNb = ?,  posLeft = ?, posTop = ?, updatedAt = ? WHERE listeId = ?";
        $this->createQuery($sql, [
            $liste->getTitle(),
            $liste->getOrderNb(),
            $liste->getPosLeft(),
            $liste->getPosTop(),
            date("Y-m-d H:i:s"),
            $liste->getListeId()
        ]);
    }

    public function getListesByBoard(int $id) {
        $sql = 'SELECT * FROM liste WHERE board_id= ? ORDER BY orderNb ASC';
        $result = $this->createQuery($sql, [$id]);
        $listes = [];

        foreach($result as $row){
            $liste = $this->buildObject($row);
            array_push($listes, $liste);
        }

        return $listes;
    }

    public function getLastListe() {
        $sql = 'SELECT * FROM liste WHERE listeId = (SELECT MAX(listeId) FROM liste)';
        $result = $this->createQuery($sql);
        $row = $result->fetch();
        $lastListe = $this->buildObject($row);

        return $lastListe;
    }

    public function removeAllListesByBoardId($id) {
        $sql = "DELETE FROM liste WHERE board_id = ?";
        $this->createQuery($sql, [$id]);
    }
}