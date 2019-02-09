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

use Rover\Fadmin\Options;

/**
 * Class SubTabControl
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTabControl extends Input
{
    /** @var array  */
    protected $subTabsConfig = array();

    /**
     * SubTabControl constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        parent::__construct($params, $options, $parent);

        if (isset($params['subTabs']) && is_array($params['subTabs']))
            $this->setSubTabsArray($params['subTabs']);
    }

    /**
     * @param array $subTabArray
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function addSubTabArray(array $subTabArray)
    {
        $this->subTabsConfig[] = $subTabArray;
    }

    /**
     * @param array $subTabsArray
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSubTabsArray(array $subTabsArray)
    {
        $this->subTabsConfig = $subTabsArray;
    }

    /**
     * @param bool $reload
     * @return array|SubTab[]
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getSubTabs($reload = false)
    {
       return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return Input[]
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren($reload = false)
    {
        if (is_null($this->children) || $reload)
            $this->loadSubTabs();

        return $this->children;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function loadSubTabs()
    {
        $this->children  = array();
        $subTabsCnt     = count($this->subTabsConfig);
        for ($i = 0; $i < $subTabsCnt; ++$i)
            $this->children[] = new SubTab($this->subTabsConfig[$i], $this->optionsEngine, $this);
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value)
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue()
    {
        return false;
    }

    /**
     * @return bool|void
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest()
    {
        $subTabs    = $this->getSubTabs();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i){
            /** @var Input $subTab */
            $subTab = $subTabs[$i];
            $subTab->setValueFromRequest();
        }
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort()
    {
        $subTabs    = $this->getSubTabs();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i){
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];
            $subTab->sort();
        }
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear()
    {
        $subTabs    = $this->getSubTabs();
        $subTabsCnt = count($subTabs);

        for ($i = 0; $i < $subTabsCnt; ++$i){
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];
            $subTab->clear();
        }
    }
}