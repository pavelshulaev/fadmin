<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 14.06.2018
 * Time: 7:47
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin\Input;

use Rover\Fadmin\Inputs\SubTab;
use Rover\Fadmin\Layout\Admin\Input;

/**
 * Class SubTabControl
 *
 * @package Rover\Fadmin\Layout\Admin\Input
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTabControl extends Input
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        ?><tr>
            <td valign="top" colspan="2" align="center"><?php

            $this->showInput();

            ?></td>
        </tr><?php
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\SubTabControl)
            return;

        $subTabs    = $this->input->getSubTabs();
        $subTabsCnt = count($subTabs);
        $initTabs   = array();

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];

            if (($subTab instanceof SubTab) && !$subTab->isHidden())
                $initTabs[] = array(
                    "DIV"   => "opt_site_" . $subTab->getValueName(),
                    "TAB"   => $subTab->getLabel(),
                    'TITLE' => $subTab->getDefault()
                );
        }

        $subTabControl = new \CAdminViewTabControl("subTabControl_" . $this->input->getValueName(), $initTabs);
        $subTabControl->Begin();

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];

            if (($subTab instanceof SubTab) && !$subTab->isHidden()) {
                $subTabControl->BeginNextTab();
                self::drawStatic($subTab);
            }
        }

        $subTabControl->End();
    }
}