<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:08
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Inputs\Input;

abstract class Layout
{
    /**
     * @var string
     */
    protected static $type;

    /**
     * @var Input
     */
    protected $input;

    /**
     * Layout constructor.
     *
     * @param Input $input
     */
    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    /**
     * @param Input $input
     * @return Layout
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function factory(Input $input)
    {
        if (!strlen(static::$type))
            throw new ArgumentNullException('type');

        $inputClassName = $input::getClassName();
        $inputType      = substr($inputClassName, strripos($inputClassName, '\\') + 1);
        $namespace      = '\\Rover\\Fadmin\\Layout\\' . static::$type . '\\' . $inputType;

        if (!class_exists($namespace))
            throw new ArgumentOutOfRangeException($namespace);

        $layoutDriver = new $namespace($input);
        if (!$layoutDriver instanceof static)
            throw new ArgumentOutOfRangeException($inputType);

        return $layoutDriver;
    }

    /**
     * @param Input $input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function drawStatic(Input $input)
    {
        $layoutDriver = self::factory($input);
        $layoutDriver->draw();
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function showInput();

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function draw();
}