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
use Rover\Fadmin\Inputs\Params\Size;
use Rover\Fadmin\Options;

/**
 * Class File
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class File extends Input
{
    use Size;

	/** @var bool */
	protected $isImage = true;

	/** @var string */
	protected $mimeType;

	/** @var int */
	protected $maxSize = 0;

    /**
     * File constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
	public function __construct(array $params, Options $options)
	{
		parent::__construct($params, $options);

		if (isset($params['isImage']))
			$this->isImage  = (bool)$params['isImage'];

		if (isset($params['maxSize']))
			$this->maxSize  = htmlspecialcharsbx($params['maxSize']);

		if (isset($params['mimeType']))
			$this->mimeType = htmlspecialcharsbx($params['mimeType']);

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval(htmlspecialcharsbx($params['size']));
		else
		    $this->size = 20;
	}

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function isImage()
    {
        return $this->isImage;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return int
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * @param int $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * @param $value
     * @return bool|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveRequest(&$value)
	{
		$request = Application::getInstance()
			->getContext()
			->getRequest();

		$value      = null;
		$valueId    = $this->getValueId();

		if (!empty($_FILES[$valueId]) && $_FILES[$valueId]['error'] == 0){

			// mime type of file checking
			if (!empty($this->mimeType) && $_FILES[$valueId]['type'] != $this->mimeType)
				throw new \Bitrix\Main\ArgumentException('incorrect file mime type');

			$value = \CFile::SaveFile($_FILES[$valueId], $this->getModuleId());

		} elseif ($request->get($valueId . '_del') == 'Y') {
			// del old value
			\CFile::Delete($this->getValue(true));
			$value = false;
		}

		return true;
	}

    /**
     * @param $value
     * @return bool
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
	public function beforeSaveValue(&$value)
	{
		// file is the same, do not save
		if ($value === null)
			return false;

		$oldValue = $this->getValue(true);

		if ($value != $oldValue)
			\CFile::Delete($oldValue);

		return true;
	}

    /**
     * @return int
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $size
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSize($size)
    {
        $this->size = intval($size);

        return $this;
    }
}