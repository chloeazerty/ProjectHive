<?php

namespace Config;

require_once 'env.local.php'; 

use Api\Controller\UserController ;
use Api\Controller\BoardController ;
use Api\Controller\ListeController;
use Api\Controller\CardController ;
use Api\Controller\BackgroundController;
use Api\Controller\MultiController;

class Router {
    private $userController;
    private $boardController;
    private $listeController;
    private $cardController;
    private $backgroundController;
    private $multiController;

    public function __construct()
    {
        $this->userController = new UserController() ; 
        $this->boardController = new BoardController() ;
        $this->listeController = new ListeController();
        $this->cardController = new CardController() ;
        $this->backgroundController = new BackgroundController();
        $this->multiController = new MultiController();
    }

    public function Run() {
        session_start();
        if($_GET) {
            // if (isset($_GET['api/board/delete'])) {
            //     $this->listeController->deleteAllListesByBoardId(4);
            //     $this->boardController->deleteBoard(4);
            // }
            if (isset($_GET['about'])) {
                require "../app/templates/about.php";
            }
            elseif (isset($_GET['mentions'])) {
                require "../app/templates/mentions.php";
            }
            elseif (isset($_GET['rgpd'])) {
                require "../app/templates/rgpd.php";
            }
            elseif(!empty($_SESSION['userId'])) {
                if (isset($_GET['profile'])) {
                    $this->userController->profile($_SESSION["userId"], $_POST);
                }
                elseif (isset($_GET['editpass'])) {
                    $this->userController->editpass($_SESSION["userId"], $_POST);
                }
                elseif (isset($_GET['logout'])) {
                    session_destroy();
                    header('Location: ?');
                }
                elseif (isset($_GET['board'])) {
                    $this->userController->showBoard();
                }
                elseif (isset($_GET['api/board'])) {
                    $this->boardController->getBoardInfos($_POST["boardId"]);
                }elseif (isset($_GET['api/board/add'])) {
                    $this->boardController->newBoard($_POST, $_SESSION["userId"]);
                }elseif (isset($_GET['api/board/edit'])) {
                    $this->boardController->editBoard($_POST);
                }elseif (isset($_GET['api/board/delete'])) {
                    $this->listeController->deleteAllListesByBoardId($_POST["boardId"]);
                    $this->boardController->deleteBoard($_POST["boardId"]);
                }
                elseif (isset($_GET['api/liste/add'])) {
                    $this->listeController->newListe($_POST);
                }elseif (isset($_GET['api/liste/update'])) {
                    $this->listeController->editListe($_POST);
                }elseif (isset($_GET['api/liste/delete'])) {
                    $this->listeController->deleteListe($_POST["listeId"]);
                }
                elseif (isset($_GET['api/card/add'])) {
                    $this->cardController->newCard($_POST);
                }elseif (isset($_GET['api/card/update'])) {
                    $this->cardController->editCard($_POST);  
                }elseif (isset($_GET['api/card/delete'])) {
                    $this->cardController->deleteCard($_POST["cardId"]);
                }
                elseif (isset($_GET['api/backgrounds'])) {
                    $this->backgroundController->getBackgrounds();
                }
                elseif (isset($_GET['api/boards'])) {
                    $this->multiController->getAllOfBoard($_POST["boardId"], $_SESSION["userId"]);
                }
                else {
                    require "../app/templates/not_found.php";
                }
            }
            elseif (isset($_GET['signup'])) {
                $this->userController->signup($_POST);
            }
            elseif (isset($_GET['registered'])) {
                require "../app/templates/registered.php";
            }
            elseif (isset($_GET['signin'])) {
                $this->userController->signin($_POST);
            }
            else {
                require "../app/templates/not_found.php";
            }
        }
        else {
            require "../app/templates/home.php";
        }
    }
}
