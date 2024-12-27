<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 23.10.2017
 * Time: 8:37
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;

/**
 * Class Preset
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Preset
{
    protected int          $id;
    protected string       $name;
    protected Options      $options;
    protected static array $instances = [];

    /**
     * Preset constructor.
     *
     * @param         $id
     * @param Options $options
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    private function __construct($id, Options $options)
    {
        $id = intval($id);
        if (!$id) {
            throw new ArgumentNullException('id');
        }

        $preset = $options->getPreset()->getById($id);
        if (!$preset) {
            throw new ArgumentOutOfRangeException('id');
        }

        $this->id      = $id;
        $this->name    = $preset['name'];
        $this->options = $options;
    }

    /**
     * @param         $id
     * @param Options $options
     * @param bool $reload
     * @return mixed
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getInstance($id, Options $options, bool $reload = false): static
    {
        $id = intval($id);
        if (!$id) {
            throw new ArgumentNullException('id');
        }

        if (!isset(self::$instances[$id]) || $reload) {
            self::$instances[$id] = new static($id, $options);
        }

        return self::$instances[$id];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function __call($name, $arguments)
    {
        if (!str_starts_with($name, 'get')) {
            throw new SystemException('unacceptable method name');
        }

        $name = substr($name, 3);

        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $name, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = strtoupper($match);
        }

        $constName = 'Options::OPTION__PRESET_' . implode('_', $ret);

        if (!defined($constName)) {
            throw new SystemException('preset option "' . $constName . '" not found');
        }

        return $this->options->getPresetValue(constant($constName), $arguments[0], $arguments[1], $arguments[2]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }
}