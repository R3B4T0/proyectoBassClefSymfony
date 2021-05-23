<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $creadoEl;

    /**
     * @return mixed
     */
    public function getCreadoEl()
    {
        return $this->creadoEl;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->creadoEl = new \DateTime();
    }
}