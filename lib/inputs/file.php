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
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

/**
 * Class File
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class File extends Input
{
	/**
	 * @var string
	 */
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
	 * @var int
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected $size = 20;

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

		if (isset($params['size']) && intval($params['size']))
			$this->size = intval(htmlspecialcharsbx($params['size']));

		// add events
		$this->addEventHandler(self::EVENT__BEFORE_SAVE_VALUE, [$this,  'beforeSaveValue']);
		$this->addEventHandler(self::EVENT__BEFORE_SAVE_REQUEST, [$this, 'beforeSaveRequest']);
	}

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
	public function showInput()
    {
        $valueName  = $this->getValueName();

        $fileType = $this->isImage
            ? 'IMAGE'
            : '';

        if (strlen($this->value) > 0):

            $file = \CFile::GetFileArray($this->value);

            echo '<code>' . $file['ORIGINAL_NAME'] . '</code><br>';

            if ($this->isImage)
                echo \CFile::ShowImage($this->value, 200, 200, "border=0", "", true) . '<br>';

        endif;

        echo \CFile::InputFile($valueName, $this->size, $this->value, false, $this->maxSize,
                $fileType, "class=typefile", 0, "class=typeinput", '', false, false)
            . '<br>';
    }

	/**
	 * @param Event $event
	 * @return EventResult|bool|int|null|string
	 * @throws \Bitrix\Main\ArgumentException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveRequest(Event $event)
	{
		if ($event->getSender() !== $this)
			return $this->getEvent()->getErrorResult($this);

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

		return $this->getEvent()->getSuccessResult($this, compact('value'));
	}

	/**
	 * not save
	 * @param Event $event
	 * @return EventResult
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveValue(Event $event)
	{
		if ($event->getSender() !== $this)
			return $this->getEvent()->getErrorResult($this);

		$value = $event->getParameter('value');

		// file is the same, do not save
		if ($value === null)
			return $this->getEvent()->getErrorResult($this);

		$oldValue = $this->getValue(true);

		if ($value != $oldValue)
			\CFile::Delete($oldValue);

		return $this->getEvent()->getSuccessResult($this, ['value' => $value]);
	}
}