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
trait Placeholder
{
    /** @var string */
    protected $placeholder;

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param $placeholder
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = trim($placeholder);

        return $this;
    }
}