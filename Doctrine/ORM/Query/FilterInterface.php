<?php

namespace Kf\KitBundle\Doctrine\ORM\Query;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
interface FilterInterface
{
    /**
     * @return array
     */
    public function getQueryParameters();
}

