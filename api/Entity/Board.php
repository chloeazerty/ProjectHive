<?php

namespace Api\Entity;

use JsonSerializable;

class Board implements JsonSerializable
{
    private $boardId;
    private $title;
    private $color;
    private $background_id;
    private $owner_id;
    private $createdAt;
    private $updatedAt;
    private $listes;

    /**
     * Get the value of boardId
     */ 
    public function getBoardId()
    {
        return $this->boardId;
    }

    /**
     * Set the value of boardId
     *
     * @return  self
     */ 
    public function setBoardId($boardId)
    {
        $this->boardId = $boardId;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of color
     */ 
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */ 
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the value of background_id
     */ 
    public function getBackground_id()
    {
        return $this->background_id;
    }

    /**
     * Set the value of background_id
     *
     * @return  self
     */ 
    public function setBackground_id($background_id)
    {
        $this->background_id = $background_id;

        return $this;
    }

    /**
     * Get the value of owner_id
     */ 
    public function getOwner_id()
    {
        return $this->owner_id;
    }

    /**
     * Set the value of owner_id
     *
     * @return  self
     */ 
    public function setOwner_id($owner_id)
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Set the value of listes
     *
     * @return  self
     */ 
    public function setListes($listes)
    {
        $this->listes = $listes;

        return $this;
    }
    

    public function jsonSerialize()
    {
        return[
            'boardId'=>$this->boardId,
            'title'=>$this->title,
            'color'=>$this->color,
            'background_id'=>$this->background_id,
            'owner_id'=>$this->owner_id,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt,
            'listes'=>$this->listes
        ];
    }

}