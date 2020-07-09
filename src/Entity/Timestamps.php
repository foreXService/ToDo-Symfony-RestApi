<?php

namespace App\Entity;

trait Timestamps
{

    /**
     *@ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     *@ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\PrePersist()
     */
    public function createdAt()
    {
        $this->createAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function updateAt()
    {
        $this->updateAt = new \DateTime();
    }

    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}