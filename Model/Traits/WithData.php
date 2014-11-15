<?php

namespace Kf\KitBundle\Model\Traits;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait WithData
{
    private $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
} 