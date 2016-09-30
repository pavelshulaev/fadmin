<?php
namespace Rover\Fadmin;

use \Bitrix\Main\Config\Option;
/**
 * Класс для манипуляции с пресетами
 * Class Presets
 * @package Fadmin
 * @author Pavel Shulaev (http://rover-it.me)
 */
class Presets
{
	const OPTION_ID = 'rover-op-presets';

	/**
	 * returns presets ids for current module and site
	 * @param        $moduleId
	 * @param string $siteId
	 * @return mixed
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function get($moduleId, $siteId = '')
	{
		return unserialize(Option::get($moduleId, self::OPTION_ID, '', $siteId));
	}

	/**
	 * @param        $moduleId
	 * @param string $siteId
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getIds($moduleId, $siteId = '')
	{
		return array_keys(self::get($moduleId, $siteId));
	}

	/**
	 * @param        $moduleId
	 * @param string $siteId
	 * @param        $id
	 * @return null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getById($id, $moduleId, $siteId = '')
	{
		$presets = self::get($moduleId, $siteId);

		if (isset($presets[$id]))
			return $presets[$id];

		return null;
	}
	/**
	 * возвращает количество пресетов
	 * @param        $moduleId
	 * @param string $siteId
	 * @return int
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function getCount($moduleId, $siteId = '')
	{
		return count(self::get($moduleId, $siteId));
	}
	
	/**
	 * @param        $moduleId
	 * @param        $name
	 * @param string $siteId
	 * @return int|mixed
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function add($moduleId, $name, $siteId = '')
	{
		$presets = self::get($moduleId, $siteId);

		if (!count($presets)){
			$presets    = [];
			$presetId   = 1;
		} else
			$presetId   = max(array_keys($presets)) + 1;

		$presets[$presetId] = [
			'id'    => $presetId,
			'name'  => htmlspecialcharsbx($name)
		];

		self::update($moduleId, $presets, $siteId);

		return $presetId;
	}

	/**
	 * removing preset id
	 * @param        $moduleId
	 * @param        $presetId
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function remove($moduleId, $presetId, $siteId = '')
	{
		$presets = self::get($moduleId, $siteId);

		foreach ($presets as $num => $preset){
			if ($presetId == $preset['id']) {
				unset($presets[$num]);
				self::update($moduleId, $presets, $siteId);
				break;
			}
		}
	}

	/**
	 * @param        $moduleId
	 * @param        $presets
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected static function update($moduleId, $presets, $siteId = '')
	{
		Option::set($moduleId, self::OPTION_ID, serialize($presets), $siteId);
	}

	/**
	 * sort presets by external function
	 * @param        $moduleId
	 * @param        $sortFunc
	 * @param string $siteId
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public static function sort($moduleId, $sortFunc, $siteId = '')
	{
		$presets = self::get($moduleId, $siteId);
		usort($presets, $sortFunc);
		self::update($moduleId, $presets, $siteId);
	}
} 