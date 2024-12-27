<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:58
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs\Params;

/**
 * Trait Size
 *
 * @package Rover\Fadmin\Inputs\Params
 */
trait Size
{
    protected int $size;

    /**
     * @return int
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }
}