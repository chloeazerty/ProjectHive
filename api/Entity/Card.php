<?php

namespace Api\Entity;

use JsonSerializable;

class Card implements JsonSerializable
{
    private $cardId;
    private $title;
    private $content;
    private $orderNb;
    private $color;
    private $liste_id;
    private $createdAt;
    private $updatedAt;


    /**
     * Get the value of cardId
     */ 
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * Set the value of cardId
     *
     * @return  self
     */ 
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;

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
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

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
     * Get the value of liste_id
     */ 
    public function getListe_id()
    {
        return $this->liste_id;
    }

    /**
     * Set the value of liste_id
     *
     * @return  self
     */ 
    public function setListe_id($liste_id)
    {
        $this->liste_id = $liste_id;

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

    public function jsonSerialize()
    {
        return[
            'cardId'=>$this->cardId,
            'title'=>$this->title,
            'content'=>$this->content,
            'orderNb'=>$this->orderNb,
            'color'=>$this->color,
            'liste_id'=>$this->liste_id,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt
        ];
    }
}