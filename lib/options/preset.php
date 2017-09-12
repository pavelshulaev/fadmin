<?php
namespace Rover\Fadmin\Options;

use Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\Config\Option;
use Rover\Fadmin\Options;
/**
 * Class Presets
 * @package Fadmin
 * @author Pavel Shulaev (http://rover-it.me)
 */
class Preset
{
	const OPTION_ID = 'rover-op-presets';

	/**
	 * @var string
	 */
	protected $options;

	/**
	 * @param Options $options
	 */
	public function __construct(Options $options)
	{
		$this->options = $options;
	}

	/**
	 * @param string $siteId
	 * @return mixed
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getList($siteId = '')
	{
		return unserialize(Option::get($this->options->getModuleId(),
			self::OPTION_ID, '', $siteId));
	}

	/**
	 * @param string $siteId
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getIds($siteId = '')
	{
		return array_keys($this->getList($siteId));
	}

	/**
	 * @param        $id
	 * @param string $siteId
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getById($id, $siteId = '')
	{
		$presets = $this->getList($siteId);

		if (isset($presets[$id]))
			return $presets[$id];

		return null;
	}

	/**
	 * @param string $siteId
	 * @return int
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getCount($siteId = '')
	{
		return count($this->getList($siteId));
	}

	/**
	 * @param        $name
	 * @param string $siteId
	 * @return int|mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function add($name, $siteId = '')
	{
		$presets = $this->getList($siteId);

		if (!count($presets)){
			$presets    = [];
			$presetId   = 1;
		} else
			$presetId   = max(array_keys($presets)) + 1;

		$presets[$presetId] = [
			'id'    => $presetId,
			'name'  => htmlspecialcharsbx($name)
		];

		$this->update($presets, $siteId);

		return $presetId;
	}

	/**
	 * @param        $id
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function remove($id, $siteId = '')
	{
		$presets = $this->getList($siteId);

		foreach ($presets as $num => $preset){
			if ($id == $preset['id']) {
				unset($presets[$num]);
				$this->update($presets, $siteId);
				break;
			}
		}
	}

	/**
	 * @param        $presets
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function update($presets, $siteId = '')
	{
		Option::set($this->options->getModuleId(),
			self::OPTION_ID, serialize($presets), $siteId);
	}

	/**
	 * sort presets by external function
	 * @param        $sortFunc
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function sort($sortFunc, $siteId = '')
	{
		$presets = $this->getList($siteId);
		usort($presets, $sortFunc);
		$this->update($presets, $siteId);
	}

	/**
	 * @param        $id
	 * @param string $siteId
	 * @return bool
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function isExists($id, $siteId = '')
	{
		return in_array($id, $this->getIds($siteId));
	}

	/**
	 * @param        $id
	 * @param        $name
	 * @param string $siteId
	 * @throws ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function updateName($id, $name, $siteId = '')
	{
		if (!$id)
			throw new ArgumentNullException('id');

		if (!$name)
			throw new ArgumentNullException('name');

		$presets = $this->getList($siteId);

		foreach ($presets as $num => &$preset){
			if ($preset['id'] != $id)
				continue;

			$preset['name'] = $name;
			break;
		}

		$this->update($presets, $siteId);
	}

	/**
	 * @param        $id
	 * @param string $siteId
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function getNameById($id, $siteId = '')
	{
		$preset = $this->getById($id, $siteId);
		if (isset($preset['name']))
			return $preset['name'];

		return null;
	}
} 