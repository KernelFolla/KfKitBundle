<?php

namespace Kf\KitBundle\Model\Traits;

/**
 * @author Marino Di Clemente <kernelfolla@gmail.com>
 */
trait WithDataClass
{
    private $dataClass;

    /**
     * @throws \Exception
     * @return string
     */
    public function getDataClass()
    {
        if (!isset($this->dataClass)) {
            if (defined('static::DATA_CLASS')) {
                $this->dataClass = static::DATA_CLASS;
            }
            //just for legacy
            if (defined('static::ENTITY_CLASS')) {
                $this->dataClass = static::ENTITY_CLASS;
            }
            if (empty($this->dataClass)) {
                throw new \Exception('missing dataClass');
            }
        }

        return $this->dataClass;
    }

    /**
     * @param string $dataClass
     * @return $this
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;

        return $this;
    }
} 