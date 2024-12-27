<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 14.06.2018
 * Time: 7:33
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

/**
 * Class SubTabControl
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTabControl extends Input
{
    protected array $subTabsConfig;

    /**
     * SubTabControl constructor.
     *
     * @param array $params
     * @param Options $options
     * @param Input|null $parent
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        parent::__construct($params, $options, $parent);

        if (isset($params['subTabs']) && is_array($params['subTabs'])) {
            $this->setSubTabsArray($params['subTabs']);
        }
    }

    /**
     * @param array $subTabArray
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function addSubTabArray(array $subTabArray): void
    {
        $this->subTabsConfig[] = $subTabArray;
        unset($this->children);
    }

    /**
     * @param array $subTabsArray
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSubTabsArray(array $subTabsArray): void
    {
        $this->subTabsConfig = $subTabsArray;
        unset($this->children);
    }

    /**
     * @param bool $reload
     * @return array|SubTab[]
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getSubTabs(bool $reload = false): array
    {
        return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return Input[]
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren(bool $reload = false): array
    {
        if (!isset($this->children) || $reload) {
            $this->loadSubTabs();
        }

        return $this->children;
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function loadSubTabs(): void
    {
        $this->children = [];
        $subTabsCnt     = count($this->subTabsConfig);
        for ($i = 0; $i < $subTabsCnt; ++$i) {
            $this->children[] = new SubTab($this->subTabsConfig[$i], $this->optionsEngine, $this);
        }
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value): bool
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue(): bool
    {
        return false;
    }

    /**
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest(): bool
    {
        $subTabs    = $this->getSubTabs();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var Input $subTab */
            $subTab = $subTabs[$i];
            $subTab->setValueFromRequest();
        }

        return true;
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort(): void
    {
        $subTabs    = $this->getSubTabs();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];
            $subTab->sort();
        }
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear(): void
    {
        $subTabs    = $this->getChildren();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];
            $subTab->clear();
        }
    }
}