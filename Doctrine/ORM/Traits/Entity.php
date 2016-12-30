<?php

namespace Kf\KitBundle\Doctrine\ORM\Traits;


use Doctrine\ORM\Mapping as ORM;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function equals($entity = null)
    {
        $class = get_class($this);
        return isset($entity) && ($entity instanceof $class  || is_subclass_of($entity,$class))
        && $entity->getID() == $this->getId();
    }
}
