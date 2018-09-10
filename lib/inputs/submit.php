<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:30
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Options;
/**
 * Class Submit
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Submit extends Input
{
	/** @var string */
	protected $popup;

    /**
     * Submit constructor.
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

		if (isset($params['popup']))
			$this->popup = $params['popup'];
	}

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	protected function beforeSaveValue(&$value)
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
     * @param $value
     * @return bool|void
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	protected function afterLoadValue(&$value)
	{
		$value = $this->default;
	}

    /**
     * @return string
     */
    public function getPopup()
    {
        return $this->popup;
    }

    /**
     * @param $popup
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPopup($popup)
    {
        $this->popup = $popup;

        return $this;
    }
}