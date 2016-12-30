<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;

use Kf\KitBundle\Functions;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait TypeableEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|array $type
     * @return boolean
     */
    public function hasType($type)
    {
        return Functions::has($this->getType(), $type);
    }
}
