<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait CreableEntity
{
    /**
     * @\Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @\Doctrine\ORM\Mapping\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
