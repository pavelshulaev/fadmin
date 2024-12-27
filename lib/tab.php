<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:34
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin;

use Bitrix\Main;
use Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Inputs\Input;
/**
 * Class Tab
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 * @deprecated
 */
class Tab extends \Rover\Fadmin\Inputs\Input
{
    /**
     * @param array $filter
     * @param bool $reload
     * @return null|Input
     * @throws ArgumentNullException
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function search(array $filter, bool $reload = false)
	{
	    $inputs     = $this->getInputs($reload);
	    $inputsCnt  = count($inputs);

	    for ($i =0; $i < $inputsCnt; ++$i) {
			/** @var Input $input */
			$input = $inputs[$i];

			if (isset($filter['id']) && strlen($filter['id'])
				&& $filter['id'] == $input->getFieldId())
				return $input;

			if (isset($filter['name']) && strlen($filter['name'])
				&& $filter['name'] == $input->getFieldName())
				return $input;
		}

		return null;
	}

    /**
     * @param bool $reload
     * @return array|string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getValue(bool $reload = false)
	{
	    $reload = trim($reload);
	    if (!strlen($reload))
            throw new ArgumentNullException('inputName');

	    return $this->getInputValue($reload, $reload);
	}

    /**
     * @param $name
     * @return null|Input
     * @throws ArgumentNullException
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated use searchOneByName
     */
	public function searchByName($name)
	{
	    return $this->searchOneByName($name);
	}

    /**
     * @return string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getName(): string
    {
	    return $this->getFieldName();
	}
}