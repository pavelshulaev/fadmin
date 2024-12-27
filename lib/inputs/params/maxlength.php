<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:00
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs\Params;

/**
 * Trait MaxLength
 *
 * @package Rover\Fadmin\Inputs\Params
 */
trait MaxLength
{
    protected int $maxLength;

    /**
     * @return int
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setMaxLength(int $maxLength): static
    {
        $this->maxLength = $maxLength;

        return $this;
    }
}