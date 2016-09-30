<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:06
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\Application;
use Rover\Fadmin\Tab;

class File extends Input
{
	public static $type = self::TYPE__FILE;

	/**
	 * @var bool
	 */
	protected $isImage = true;

	/**
	 * @var string
	 */
	protected $mimeType;

	/**
	 * @var int
	 */
	protected $maxSize = 0;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		parent::__construct($params, $tab);

		if (isset($params['isImage']))
			$this->isImage  = (bool)$params['isImage'];

		if (isset($params['maxSize']))
			$this->maxSize  = htmlspecialcharsbx($params['maxSize']);

		if (isset($params['mimeType']))
			$this->mimeType = htmlspecialcharsbx($params['mimeType']);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		$fileType = $this->isImage
			? 'IMAGE'
			: '';

		echo \CFile::InputFile($valueName, $this->size, $this->value, false, $this->maxSize, $fileType, "class=typefile", 0, "class=typeinput", '', false, false, false);

		if (strlen($this->value) > 0 && $this->isImage):
			?><br><br><?echo \CFile::ShowImage($this->value, 200, 200, "border=0", "", true);
		endif;

		$this->showHelp();
	}

	/**
	 * @param $value
	 * @return bool|int|null|string
	 * @throws \Bitrix\Main\ArgumentException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveRequest($value)
	{
		$request = Application::getInstance()
			->getContext()
			->getRequest();

		$value = null;

		$valueId = $this->getValueId();

		if (!empty($_FILES[$valueId]) && $_FILES[$valueId]['error'] == 0){

			// mime type of file checking
			if (!empty($this->mimeType) && $_FILES[$valueId]['type'] != $this->mimeType)
				throw new \Bitrix\Main\ArgumentException('incorrect file mime type');

			$value = \CFile::SaveFile($_FILES[$valueId], $this->tab->getModuleId());

		} elseif ($request->get($valueId . '_del') == 'Y') {
			// del old value
			\CFile::Delete($this->getValue(true));
			$value = false;
		}


		return $value;
	}

	/**
	 * @param array $params
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function beforeSaveValue(array $params)
	{
		$value = $params['value'];

		if ($value === null)
			return false;

		$oldValue = $this->getValue(true);

		if ($value != $oldValue)
			\CFile::Delete($oldValue);

		return $value;
	}
}