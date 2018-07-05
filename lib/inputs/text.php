<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:26
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Rover\Fadmin\Inputs\Params\MaxLength;

use Rover\Fadmin\Inputs\Params\Placeholder;
use Rover\Fadmin\Inputs\Params\Size;
use Rover\Fadmin\Options;

/**
 * Class Text
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Text extends Input
{
    use MaxLength, Size, Placeholder;

    /**
     * Text constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options, Input $parent = null)
	{
		parent::__construct($params, $options, $parent);

		if (isset($params['maxLength']) && intval($params['maxLength']))
		    $this->setMaxLength($params['maxLength']);
		else
            $this->setMaxLength(255);

		if (isset($params['size']) && intval($params['size']))
		    $this->setSize(htmlspecialcharsbx($params['size']));
		else
            $this->setSize(50);

        if (isset($params['placeholder']))
            $this->setPlaceholder($params['placeholder']);
	}
}