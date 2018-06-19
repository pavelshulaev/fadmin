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
     * @param bool  $reload
     * @return null|Input
     * @throws ArgumentNullException
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function search(array $filter, $reload = false)
	{
	    $inputs     = $this->getInputs($reload);
	    $inputsCnt  = count($inputs);

	    for ($i =0; $i < $inputsCnt; ++$i) {
			/** @var Input $input */
			$input = $inputs[$i];

			if (isset($filter['id']) && strlen($filter['id'])
				&& $filter['id'] == $input->getValueId())
				return $input;

			if (isset($filter['name']) && strlen($filter['name'])
				&& $filter['name'] == $input->getValueName())
				return $input;
		}

		return null;
	}

    /**
     * @param bool $inputName
     * @param bool $reload
     * @return array|string
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
	public function getValue($inputName = false, $reload = false)
	{
	    $inputName = trim($inputName);
	    if (!strlen($inputName))
            throw new ArgumentNullException('inputName');

	    return $this->getInputValue($inputName, $reload);
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
        $valueName  = self::getFullPath($name, $this->getPresetId(), $this->getSiteId());
        $inputs     = $this->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var Input $input */
            $input = $inputs[$i];
            if ($input->getValueName() == $valueName)
                return $input;
        }

		return null;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated use getValueName
	 */
	public function getName()
	{
		return self::getFullPath($this->name, $this->presetId, $this->siteId);
	}
}