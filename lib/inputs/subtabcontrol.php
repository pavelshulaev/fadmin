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

use Rover\Fadmin\Tab;

/**
 * Class SubTabControl
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTabControl extends Input
{
    /** @var SubTab[] */
    protected $subTabs = array();

    /**
     * Subtabcontrol constructor.
     *
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Tab $tab)
    {
        parent::__construct($params, $tab);

        if (isset($params['subTabs']) && is_array($params['subTabs'])){
            $subTabsCnt = count($params['subTabs']);
            for ($i = 0; $i < $subTabsCnt; ++$i)
                $this->subTabs[] = new SubTab($params['subTabs'][$i], $tab);
        }
    }

    /**
     * @return SubTab[]
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getSubTabs()
    {
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