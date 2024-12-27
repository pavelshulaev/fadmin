<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:08
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Rover\Fadmin\Inputs\Input as InputEngine;

/**
 * Class Layout
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Input
{
    public static string  $type;
    protected InputEngine $input;

    /**
     * Layout constructor.
     *
     * @param InputEngine $input
     */
    public function __construct(InputEngine $input)
    {
        $this->input = $input;
    }

    /**
     * @param InputEngine $input
     * @return Input
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function build(InputEngine $input): Input
    {
        if (!strlen(static::$type)) {
            throw new ArgumentNullException('type');
        }

        $inputClassName = $input::getClassName();
        $inputType      = substr($inputClassName, strripos($inputClassName, '\\') + 1);
        $namespace      = '\\Rover\\Fadmin\\Layout\\' . static::$type . '\\Input\\' . $inputType;

        if (!class_exists($namespace)) {
            throw new ArgumentOutOfRangeException($namespace);
        }

        $layoutDriver = new $namespace($input);
        if (!$layoutDriver instanceof self) {
            throw new ArgumentOutOfRangeException($inputType);
        }

        return $layoutDriver;
    }

    /**
     * @param InputEngine $input
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function drawStatic(InputEngine $input): void
    {
        $layoutDriver = self::build($input);
        $layoutDriver->draw();
    }

    /**
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function showInput(): void;

    /**
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPreInput(): void
    {
    }

    /**
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPostInput(): void
    {
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputLayout(): string
    {
        ob_start();
        $this->showInput();

        return ob_get_clean();
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getType(): string
    {
        $input = $this->input;

        return $input::getType();
    }

    /**
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function draw(): void;
}