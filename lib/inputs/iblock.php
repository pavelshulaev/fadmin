<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:50
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin\Inputs;

class Iblock extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__IBLOCK;

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function draw()
	{
		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		if ($this->multiple)
			echo self::getIBlockDropDownListMultiple($this->value, $this->name . '_type', $valueName);
		else
			echo GetIBlockDropDownList($this->value, $this->name . '_type', $valueName);

		$this->showHelp();
	}


	/**
	 * @param $value
	 * @return string
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function beforeSaveRequest($value)
	{
		if ($this->multiple)
			$value = serialize($value);

		return $value;
	}

	/**
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected function afterLoadValue()
	{
		if ($this->multiple) {
			$this->value = unserialize($this->value);
			if (!$this->value)
				$this->value = [];
		} elseif (!$this->value) {
			$this->value = 0;
		}
	}


	/**
	 * @param array      $iblockIds
	 * @param            $strTypeName
	 * @param            $strIBlockName
	 * @param bool|false $arFilter
	 * @param string     $onChangeType
	 * @param string     $onChangeIBlock
	 * @param string     $strAddType
	 * @param string     $strAddIBlock
	 * @return string
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	protected static function getIBlockDropDownListMultiple(array $iblockIds, $strTypeName, $strIBlockName, $arFilter = false, $onChangeType = '', $onChangeIBlock = '', $strAddType = '', $strAddIBlock = '')
	{
		$html = '';

		static $arTypesAll  = [];
		static $arTypes     = [];
		static $arIBlocks   = [];

		if(!is_array($arFilter))
			$arFilter = array();
		if (!array_key_exists('MIN_PERMISSION',$arFilter) || trim($arFilter['MIN_PERMISSION']) == '')
			$arFilter["MIN_PERMISSION"] = "W";
		$filterId = md5(serialize($arFilter));

		if(!isset($arTypes[$filterId]))
		{
			$arTypes[$filterId]     = array(0 => GetMessage("IBLOCK_CHOOSE_IBLOCK_TYPE"));
			$arIBlocks[$filterId]   = array(0 => array(''=>GetMessage("IBLOCK_CHOOSE_IBLOCK")));

			$rsIBlocks = \CIBlock::GetList(array("IBLOCK_TYPE" => "ASC", "ID" => "ASC"), $arFilter);
			while($arIBlock = $rsIBlocks->Fetch())
			{
				$tmpIBLOCK_TYPE_ID = $arIBlock["IBLOCK_TYPE_ID"];
				if(!array_key_exists($tmpIBLOCK_TYPE_ID, $arTypesAll))
				{
					$arType = \CIBlockType::GetByIDLang($tmpIBLOCK_TYPE_ID, LANG);
					$arTypesAll[$arType["~ID"]] = $arType["~NAME"]." [".$arType["~ID"]."]";
				}
				if(!array_key_exists($tmpIBLOCK_TYPE_ID, $arTypes[$filterId]))
				{
					$arTypes[$filterId][$tmpIBLOCK_TYPE_ID] = $arTypesAll[$tmpIBLOCK_TYPE_ID];
					$arIBlocks[$filterId][$tmpIBLOCK_TYPE_ID] = array(0 => GetMessage("IBLOCK_CHOOSE_IBLOCK"));
				}
				$arIBlocks[$filterId][$tmpIBLOCK_TYPE_ID][$arIBlock["ID"]] = $arIBlock["NAME"]." [".$arIBlock["ID"]."]";
			}

			$html .= '
			<script type="text/javascript">
			function OnType_'.$filterId.'_Changed(typeSelect, iblockSelectID)
			{
				var arIBlocks = '.\CUtil::PhpToJSObject($arIBlocks[$filterId]).';
				var iblockSelect = BX(iblockSelectID);
				if(!!iblockSelect)
				{
					for(var i=iblockSelect.length-1; i >= 0; i--)
						iblockSelect.remove(i);
					for(var j in arIBlocks[typeSelect.value])
					{
						var newOption = new Option(arIBlocks[typeSelect.value][j], j, false, false);
						iblockSelect.options.add(newOption);
					}
				}
			}
			</script>
			';
		}

		$IBLOCK_TYPE = false;
		if(count($iblockIds) > 0)
		{
			foreach($arIBlocks[$filterId] as $iblock_type_id => $iblocks)
			{
				if(array_key_exists(reset($iblockIds), $iblocks))
				{
					$IBLOCK_TYPE = $iblock_type_id;
					break;
				}
			}
		}

		$htmlTypeName = htmlspecialcharsbx($strTypeName);
		$htmlIBlockName = htmlspecialcharsbx($strIBlockName);
		$onChangeType = 'OnType_'.$filterId.'_Changed(this, \''.\CUtil::JSEscape($strIBlockName).'\');'.$onChangeType.';';
		$onChangeIBlock = trim($onChangeIBlock);

		$html .= '<select name="'.$htmlTypeName.'" id="'.$htmlTypeName.'" onchange="'.htmlspecialcharsbx($onChangeType).'" '.$strAddType.'>'."\n";
		foreach($arTypes[$filterId] as $key => $value)
		{
			if($IBLOCK_TYPE === false)
				$IBLOCK_TYPE = $key;
			$html .= '<option value="'.htmlspecialcharsbx($key).'"'.($IBLOCK_TYPE===$key? ' selected': '').'>'.htmlspecialcharsEx($value).'</option>'."\n";
		}
		$html .= "</select>\n";
		$html .= "&nbsp;\n";
		$size = count($arIBlocks[$filterId][$IBLOCK_TYPE]) > 5 ? '8' : '3';
		$html .= '<select multiple="multiple" size="' . $size .'"  name="'.$htmlIBlockName.'[]" id="'.$htmlIBlockName.'"'.($onChangeIBlock != ''? ' onchange="'.htmlspecialcharsbx($onChangeIBlock).'"': '').' '.$strAddIBlock.'>'."\n";
		foreach($arIBlocks[$filterId][$IBLOCK_TYPE] as $key => $value)
		{
			$html .= '<option value="'.htmlspecialcharsbx($key).'"'.(in_array($key, $iblockIds)? ' selected': '').'>'.htmlspecialcharsEx($value).'</option>'."\n";
		}
		$html .= "</select>\n";

		return $html;
	}
}