<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait SoftDeleteableEntity
{
    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    public function isDeleted(){
        return $this->getDeletedAt() instanceof \DateTime;
    }
}
