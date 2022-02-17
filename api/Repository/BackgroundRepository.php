<?php

namespace Api\Repository;

use Api\Entity\Background;

class BackgroundRepository extends ManagerRepository
{
    public function addBackground(object $background)
    {
        $sql = 'INSERT INTO background (imageUrl, createdAt, updatedAt) VALUES (?, NOW(), NOW())';
        $this->createQuery($sql, [
            $background->getImageUrl(),
        ]);
    }
}