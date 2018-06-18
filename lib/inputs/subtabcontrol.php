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
    /** @var SubTab[] */
    protected $subTabs;

    /** @var array  */
    protected $subTabsConfig = array();

    /**
     * SubTabControl constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Options $options)
    {
        parent::__construct($params, $options);

        if (isset($params['subTabs']) && is_array($params['subTabs']))
            $this->subTabsConfig = $params['subTabs'];
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
        if (is_null($this->subTabs) || $reload) {
            $this->subTabs  = array();
            $subTabsCnt     = count($this->subTabsConfig);
            for ($i = 0; $i < $subTabsCnt; ++$i)
                $this->subTabs[] = new SubTab($this->subTabsConfig[$i], $this->optionsEngine);
        }

        return $this->subTabs;
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
}