<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 21.01.2016
 * Time: 20:18
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Options;

use \Bitrix\Main\ArgumentNullException;;

use Bitrix\Main\ArgumentOutOfRangeException;
use \Rover\Fadmin\Options;
use \Rover\Fadmin\Inputs\Tab;
use \Rover\Fadmin\Inputs\Input;

/**
 * Class TabMap
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 * @deprecated
 */
class TabMap
{
	/** @var Options */
	protected $options;

    /**
     * TabMap constructor.
     *
     * @param Options $options
     * @deprecated
     */
	public function __construct(Options $options)
	{
		$this->options = $options;
	}

    /**
     * @param bool $reload
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getTabs($reload = false)
	{
        return $this->options->getTabControl()->getTabs($reload);
	}

    /**
     * @param bool $reload
     * @return \Rover\Fadmin\Inputs\Tab[]
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getAdminTabs($reload = false)
    {
        return $this->options->getTabControl()->getAdminTabs($reload);
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function reloadTabs()
    {
        return $this->options->getTabControl()->reloadTabs();
    }

    /**
     * @param        $presetId
     * @param string $siteId
     * @param bool   $reload
     * @return mixed|null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function getTabByPresetId($presetId, $siteId = '', $reload = false)
	{
        return $this->options->getTabControl()->getTabByPresetId($presetId, $siteId, $reload);
	}

    /**
     * @param        $name
     * @param string $siteId
     * @param bool   $reload
     * @return mixed|null|Tab
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function searchTabByName($name, $siteId = '', $reload = false)
	{
	    return $this->options->getTabControl()->searchTabByName($name, $siteId, '', $reload);
	}

    /**
     * @param        $inputName
     * @param string $presetId
     * @param string $siteId
     * @param bool   $reload
     * @return null|Input
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function searchInputByName($inputName, $presetId = '', $siteId = '', $reload = false)
    {
        return $this->options->getTabControl()->searchOneByName($inputName, $presetId, $siteId);
    }

    /**
     * @param bool $admin
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function setValuesFromRequest($admin = false)
    {
        return $this->options->getTabControl()->setValueFromRequest($admin);
    }

    /**
     * @param        $value
     * @param string $siteId
     * @return bool|int|mixed
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function addPreset($value, $siteId = '')
    {
        return $this->options->preset->add($value, $siteId);
    }

    /**
     * @param        $id
     * @param string $siteId
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function removePreset($id, $siteId = '')
    {
        return $this->options->preset->remove($id, $siteId);
    }
}