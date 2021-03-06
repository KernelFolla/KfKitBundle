<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

use Kf\KitBundle\Functions;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait StatusableEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string|array $status
     * @return boolean
     */
    public function hasStatus($status)
    {
        return Functions::has($this->getStatus(), $status);
    }
}
