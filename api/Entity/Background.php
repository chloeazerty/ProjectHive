<?php

namespace Api\Entity;

use JsonSerializable;

class Background implements JsonSerializable
{
    private $backgroundId;
    private $imageUrl;
    private $createdAt;
    private $updatedAt;
    

    /**
     * Get the value of backgroundId
     */ 
    public function getBackgroundId()
    {
        return $this->backgroundId;
    }

    /**
     * Set the value of backgroundId
     *
     * @return  self
     */ 
    public function setBackgroundId($backgroundId)
    {
        $this->backgroundId = $backgroundId;

        return $this;
    }

    /**
     * Get the value of imageUrl
     */ 
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the value of imageUrl
     *
     * @return  self
     */ 
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

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
            'backgroundId'=>$this->backgroundId,
            'imageUrl'=>$this->imageUrl,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt
        ];
    }
}