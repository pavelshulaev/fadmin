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
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        ?>
        <style>
            #bx-admin-prefix .adm-detail-subtabs-block{
                white-space: normal!important;
            }
        </style>
        <?php $this->showRowStart(); ?>
            <td valign="top" colspan="2" align="center"><?php

            echo '<h3 style="text-align: left">' . $this->input->getLabel() . '</h3>';

            $this->showInput();

            ?></td><?php
        $this->showRowEnd();
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showInput()
    {
        if (!$this->input instanceof \Rover\Fadmin\Inputs\SubTabControl)
            return;

        $subTabs    = $this->input->getSubTabs(true);
        $subTabsCnt = count($subTabs);
        $initTabs   = array();

        for ($i = 0; $i < $subTabsCnt; ++$i) {
            /** @var SubTab $subTab */
            $subTab = $subTabs[$i];

            if (($subTab instanceof SubTab) && !$subTab->isHidden())
                $initTabs[] = array(
                    "DIV"   => "opt_site_" . $this->input->getFieldName() . '_' . $subTab->getFieldName(),
                    "TAB"   => $subTab->getLabel(),
                    'TITLE' => $subTab->getDefault(),
                    //'DESCRIPTION' => $subTab
                );
        }

        $subTabControl = new \CAdminViewTabControl("subTabControl_" . $this->input->getFieldName(), $initTabs);
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