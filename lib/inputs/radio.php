<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Tab;
/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Radio extends Input
{
	public static $type = self::TYPE__RADIO;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['options']))
			$this->options = $params['options'];
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		foreach ($this->options as $optionValue => $optionName):

            ?><label><input
                type="radio"
                <?=$this->disabled ? 'disabled="disabled"': '';?>
                name="<?=$valueName?>"
                id="<?=$valueId?>"
                value="<?=$optionValue?>"
                <?=$this->value == $optionValue ? ' checked="checked "' : ''?>
            ><?=$optionName?></label><?php

        endforeach;

		$this->showHelp();
	}

	/**
	 * @param array $options
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getOptions()
    {
        return $this->options;
    }
}