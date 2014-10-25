<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait TimestampableEntity
{
    use CreableEntity;

    /**
     * @\Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @\Doctrine\ORM\Mapping\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}
