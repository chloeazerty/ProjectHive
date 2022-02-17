<?php

namespace Api\Repository;

use Api\Entity\Board;

class BoardRepository extends ManagerRepository
{
    public function addBoard(object $board)
    {
        $sql = 'INSERT INTO board (title, color, background_id, owner_id, createdAt, updatedAt) VALUES (?, ?, ?, ?, NOW(), NOW())';
        $this->createQuery($sql, [
            $board->getTitle(),
            $board->getColor(),
            $board->getBackground_id(),
            $board->getOwner_id()
        ]);
    }

    public function defaultBoard($username, $userId)
    {
        $sql = 'INSERT INTO board (title, color, background_id, owner_id, createdAt, updatedAt) VALUES (?, ?, ?, ?, NOW(), NOW())';
        $this->createQuery($sql, [
            "Tableau de $username",
            NULL,
            1,
            $userId
        ]);
    }

    public function editBoard($board)
    {
        $sql = "UPDATE board SET title = ?,  color = ?, background_id = ?, updatedAt = ? WHERE boardId = ?";
        $this->createQuery($sql, [
            $board->getTitle(),
            $board->getColor(),
            $board->getBackground_id(),
            date("Y-m-d H:i:s"),
            $board->getBoardId()
        ]);
    }

    public function getBoardsByOwnerId(int $id) {
        $sql = "SELECT * FROM board WHERE owner_id = ?";
        $result = $this->createQuery($sql, [$id]);
        $boards = [];

        foreach($result as $row){
            $board = $this->buildObject($row);
            array_push($boards, $board);
        }

        return $boards;
    }

    public function getLastBoard() {
        $sql = 'SELECT * FROM board WHERE boardId = (SELECT MAX(boardId) FROM board)';
        $result = $this->createQuery($sql);
        $row = $result->fetch();
        $lastBoard = $this->buildObject($row);

        return $lastBoard;
    }
}

