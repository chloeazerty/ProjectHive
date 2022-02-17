<?php

namespace Api\Entity;

use JsonSerializable;

class Liste implements JsonSerializable
{
    private $listeId;
    private $title;
    private $orderNb;
    private $board_id;
    private $posLeft;
    private $posTop;
    private $createdAt;
    private $updatedAt;
    private $cards;

    /**
     * Get the value of listeId
     */ 
    public function getListeId()
    {
        return $this->listeId;
    }

    /**
     * Set the value of listeId
     *
     * @return  self
     */ 
    public function setListeId($listeId)
    {
        $this->listeId = $listeId;

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
     * Get the value of orderNb
     */ 
    public function getOrderNb()
    {
        return $this->orderNb;
    }

    /**
     * Set the value of orderNb
     *
     * @return  self
     */ 
    public function setOrderNb($orderNb)
    {
        $this->orderNb = $orderNb;

        return $this;
    }

    /**
     * Get the value of board_id
     */ 
    public function getBoard_id()
    {
        return $this->board_id;
    }

    /**
     * Set the value of board_id
     *
     * @return  self
     */ 
    public function setBoard_id($board_id)
    {
        $this->board_id = $board_id;

        return $this;
    }

    /**
     * Get the value of posLeft
     */ 
    public function getPosLeft()
    {
        return $this->posLeft;
    }

    /**
     * Set the value of posLeft
     *
     * @return  self
     */ 
    public function setPosLeft($posLeft)
    {
        $this->posLeft = $posLeft;

        return $this;
    }

    /**
     * Get the value of posTop
     */ 
    public function getPosTop()
    {
        return $this->posTop;
    }

    /**
     * Set the value of posTop
     *
     * @return  self
     */ 
    public function setPosTop($posTop)
    {
        $this->posTop = $posTop;

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
     * Set the value of cards
     *
     * @return  self
     */ 
    public function setCards($cards)
    {
        $this->cards = $cards;

        return $this;
    }

    public function jsonSerialize()
    {
        return[
            'listeId'=>$this->listeId,
            'title'=>$this->title,
            'orderNb'=>$this->orderNb,
            'board_id'=>$this->board_id,
            'posLeft'=>$this->posLeft,
            'posTop'=>$this->posTop,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt,
            'cards'=>$this->cards
        ];
    }
}
