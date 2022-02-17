<?php

namespace Api\Controller;

use Api\Entity\Background;
use Api\Repository\BackgroundRepository;
use Api\Controller\AbstractController;


class BackgroundController extends AbstractController
{
    private $backgroundRepository;

    public function __construct()
    {
        $this->backgroundRepository = new BackgroundRepository();
    }

    public function getBackgrounds()
    {
        $backgrounds = $this->backgroundRepository->findAll();

        echo json_encode($backgrounds);
    }

    public function newBackground($post)
    {
        if (isset($post['submit'])) {
            $background = new Background();
            $background
                ->setImageUrl($post["imageUrl"]);

            $this->backgroundRepository->addBackground($background);
        }
    }
}