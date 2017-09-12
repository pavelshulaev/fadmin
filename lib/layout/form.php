<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 17:11
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout;

use Rover\Fadmin\Options;

/**
 * Class FormAbstract
 *
 * @package Rover\Fadmin\Layout
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Form
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * @var array
     */
    protected $params;

    /**
     * Form constructor.
     *
     * @param Options $options
     * @param array   $params
     */
    public function __construct(Options $options, array $params = [])
    {
        $this->options  = $options;
        $this->params   = $params;
    }

    abstract public function show();
}