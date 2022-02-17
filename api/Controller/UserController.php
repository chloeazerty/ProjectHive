<?php

namespace Api\Controller;

use Api\DataFixtures\UserFixtures;
use Api\Entity\User;
use Api\Repository\UserRepository;
use Api\Repository\BoardRepository;
use Api\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepository;
    private $boardRepository;
    private $message;
    private $boardsList;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->boardRepository = new BoardRepository();
    }

    public function loadFixtures()
    {
        UserFixtures::load();
        header('Location: ?');
    }

    public function signup($user)
    {
        if (isset($user['submit'])) {
            $this->message = $this->userRepository->addUser($user);
        }

        $this->render("signup", [
            'message' => $this->message
        ]);
    }

    public function signin($user)
    {
        if (isset($user['submit'])) {
            $this->message = $this->userRepository->connectUser($user);
        }
        $this->render("signin", [
            'message' => $this->message,
        ]);
    }

    public function showBoard() {
        if(isset($_SESSION['userId'])) {
            $this->boardsList = $this->boardRepository->getBoardsByOwnerId($_SESSION['userId']);
            if(empty($this->boardsList)) {
                $this->boardRepository->defaultBoard($_SESSION['username'], $_SESSION['userId']);
                $this->boardsList = $this->boardRepository->getBoardsByOwnerId($_SESSION['userId']);
            }
            $this->render("board", [
                'boardsList' => json_encode($this->boardsList)
            ]);
        }
    }

    public function profile($id, $post)
    {
        if(isset($post['submit'])) {
            $this->message = $this->userRepository->editUser($id, $post);
        }

        $user = $this->userRepository->findOne($id);

        $this->render("profile", [
            'user' => $user,
            'message' => $this->message
        ]);
    }

    public function editpass($id, $post)
    {
        if(isset($post['submit'])) {
            $this->message = $this->userRepository->editpass($id, $post);
        }
        $this->render("editpass", [
            'message' => $this->message
        ]);
    }
}

