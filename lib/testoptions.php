<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 21.01.2016
 * Time: 21:08
 *
 * @author Shulaev (pavel.shulaev@gmail.com)
 */

namespace Rover\Fadmin;

use Rover\Fadmin\Inputs\Input;

class TestOptions extends Options
{
	/**
	 * @return array
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getConfig()
	{
		return [
			'tabs' => [
				[
					'name'          => 'test_tab',
					'label'         => 'test tab',
					'description'   => 'test tab description',
					'siteId'        => 's1',
					'inputs'        => [
						[
							'type'      => Input::TYPE__HEADER,
							'name'      => 'input_header',
							'label'     => 'First tab header',
						],
						[
							'type'      => Input::TYPE__TEXTAREA,
							'name'      => 'input_textarea',
							'label'     => 'input textarea 3 rows 20 cols',
							'default'   => 'default text',
							'rows'      => 3,
							'cols'      => 20,
							'help'      => 'textarea help',
						],
						[
							'type'      => Input::TYPE__ADD_PRESET,
							'name'      => 'add_preset',
							'label'     => 'add preset',
							'default'   => 'new preset default name',
							'popup'     => 'add preset popup' // false - not show
						]
					]
				],
				[
					'name'          => 'presetTab',
					'label'         => 'Preset',
					'preset'        => true,
					'description'   => 'This is a description of preset tab',
					'siteId'        => 's1',
					'inputs'        => [
						[
							'type'      => Input::TYPE__HEADER,
							'name'      => 'preset_header',
							'label'     => 'Preset header',
						],
						[
							'type'      => Input::TYPE__COLOR,
							'name'      => 'preset_color',
							'label'     => 'preset color',
							'default'   => '#FFAA00',
							'help'      => 'color help',
						],
						[
							'type'      => Input::TYPE__REMOVE_PRESET,
							'name'      => 'remove_preset',
							'label'     => 'remove_preset',
							'popup'     => 'Are you sure?',
						]
					],
				],
				[
					'name'          => 'tab_22',
					'label'         => '2 normal tab',
					'description'   => 'This is a description of second normal tab',
					'siteId'        => 'm',
					'inputs'        => [
						[
							'type'      => Input::TYPE__SELECTBOX,
							'name'      => 'selectbox',
							'label'     => 'Multiple selectbox',
							'multiple'  => true,
							'options'   => [
								'0' => '123',
								'1' => '456',
								'2' => '789'
							]
						],
						[
							'type'      => Input::TYPE__ADD_PRESET,
							'name'      => 'add_preset',
							'label'     => 'add preset',
							'default'   => 'new preset default name',
							'popup'     => 'add preset popup 2' // false - not show
						]
					]
				],
				[
					'name'          => 'presetTab2',
					'label'         => 'Preset2',
					'preset'        => true,
					'description'   => 'This is a description of preset tab 2',
					'siteId'        => 'm',
					'inputs'        => [
						[
							'type'      => Input::TYPE__HEADER,
							'name'      => 'preset_header',
							'label'     => 'Preset header',
						],
						[
							'type'      => Input::TYPE__COLOR,
							'name'      => 'preset_color',
							'label'     => 'preset color',
							'default'   => '#FFAA00',
							'help'      => 'color help',
						],
						[
							'type'      => Input::TYPE__REMOVE_PRESET,
							'name'      => 'remove_preset',
							'label'     => 'remove_preset',
							'popup'     => 'Are you sure?',
						]
					],
				],
			]
		];
	}

	/**
	 * @param bool|false $reload
	 * @return mixed|null
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getTextareaValueS1($reload = false)
	{
		return $this->getNormalValue('input_textarea', 's1', $reload);
	}

	/**
	 * @param            $presetId
	 * @param bool|false $reload
	 * @return mixed|null
	 * @author Shulaev (pavel.shulaev@gmail.com)
	 */
	public function getS1PresetColor($presetId, $reload = false)
	{
		return $this->getPresetValue('preset_color', $presetId, 's1', $reload);
	}
}