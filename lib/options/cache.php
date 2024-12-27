<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 16.01.2018
 * Time: 17:13
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Options;

use Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Options;

/**
 * Class Cache
 *
 * @package Rover\Fadmin\Options
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Cache
{
    protected Options $options;
    protected array   $cache;

    /**
     * Cache constructor.
     *
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @param        $cacheId
     * @param        $value
     * @param string $container
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function set($cacheId, $value, string $container = 'value'): void
    {
        if (!strlen($cacheId)) {
            throw new ArgumentNullException('cacheId');
        }

        if (!strlen($container)) {
            throw new ArgumentNullException('container');
        }

        $this->cache[$container][$cacheId] = $value;
    }

    /**
     * @param        $cacheId
     * @param string $container
     * @return null
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function get($cacheId, string $container = 'value')
    {
        if (!strlen($cacheId)) {
            throw new ArgumentNullException('cacheId');
        }

        if (!strlen($container)) {
            throw new ArgumentNullException('container');
        }

        if (self::check($cacheId, $container)) {
            return $this->cache[$container][$cacheId];
        }

        return null;
    }

    /**
     * @param $cacheId
     * @param string $container
     * @return bool
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function check($cacheId, string $container = 'value'): bool
    {
        if (!strlen($cacheId)) {
            throw new ArgumentNullException('cacheId');
        }

        if (!strlen($container)) {
            throw new ArgumentNullException('container');
        }

        return isset($this->cache[$container][$cacheId]);
    }

    /**
     * @param string $container
     * @return bool
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function checkContainer(string $container): bool
    {
        if (!strlen($container)) {
            throw new ArgumentNullException('container');
        }

        return isset($this->cache[$container]);
    }

    /**
     * @param        $cacheId
     * @param string $container
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function remove($cacheId, string $container = 'value'): void
    {
        if (!strlen($cacheId)) {
            throw new ArgumentNullException('cacheId');
        }

        if (!strlen($container)) {
            throw new ArgumentNullException('container');
        }

        unset($this->cache[$container][$cacheId]);
    }
}