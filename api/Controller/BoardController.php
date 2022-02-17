<?php

namespace Api\Controller;

use Api\Entity\Board;
use Api\Repository\BoardRepository;
use Api\Controller\AbstractController;


class BoardController extends AbstractController
{
    private $boardRepository;

    public function __construct()
    {
        $this->boardRepository = new BoardRepository();
    }

    public function getBoardInfos($id)
    {
        $board = $this->boardRepository->findOne($id);

        echo json_encode($board);
    }

    public function newBoard($post, $owner_id) //enregistre un tableau dans la bdd
    {
        if ($post) {
            $board = new Board();
            $board
                ->setTitle($post["title"])
                ->setColor($post["color"] ?: NULL)
                ->setBackground_id($post["background_id"] ?: NULL)
                ->setOwner_id($owner_id);

            $this->boardRepository->addBoard($board);
            $lastBoard = $this->boardRepository->getLastBoard();
            echo json_encode($lastBoard); 
        }
    }

    public function editBoard($post)
    {
        $board = $this->boardRepository->findOne($post['boardId']);

        $board
            ->setTitle($post["title"])
            ->setColor($post["color"])
            ->setBackground_id($post["background_id"] ?: NULL);

        $this->boardRepository->editBoard($board);
        $updatedBoard = $this->boardRepository->findOne($post['boardId']);
        echo json_encode($updatedBoard); 
    }

    public function deleteBoard($id)
    {
        $delete = $this->boardRepository->delete($id);
        if($delete == true) {
            http_response_code(200);
        }
    }

}

