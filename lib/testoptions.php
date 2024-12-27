<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 21.01.2016
 * Time: 21:08
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Inputs\Checkbox;
use Rover\Fadmin\Inputs\Clock;
use Rover\Fadmin\Inputs\Color;
use Rover\Fadmin\Inputs\File;
use Rover\Fadmin\Inputs\Header;
use Rover\Fadmin\Inputs\Iblock;
use Rover\Fadmin\Inputs\Number;
use Rover\Fadmin\Inputs\Password;
use Rover\Fadmin\Inputs\PresetName;
use Rover\Fadmin\Inputs\Radio;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Inputs\Row;
use Rover\Fadmin\Inputs\Schedule;
use Rover\Fadmin\Inputs\Selectbox;
use Rover\Fadmin\Inputs\Selectgroup;
use Rover\Fadmin\Inputs\SubTab;
use Rover\Fadmin\Inputs\SubTabControl;
use Rover\Fadmin\Inputs\Text;
use Rover\Fadmin\Inputs\Textarea;
use Rover\Fadmin\Options\Settings;

/**
 * Class TestOptions
 *
 * @package Rover\Fadmin
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class TestOptions extends Options
{
    const MODULE_ID = 'rover.fadmin';

    const OPTION__PRESET_COLOR = 'preset_color';
    const OPTION__TEXTAREA     = 'input_textarea';

    /**
     * @param string $moduleId
     * @return TestOptions
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function getInstance($moduleId = self::MODULE_ID): static
    {
        return parent::getInstance(self::MODULE_ID);
    }

    /**
     * @return array
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getConfig(): array
    {
        return [
            'tabs'     => self::getTabs(),
            'settings' => [
                Settings::BOOL_CHECKBOX => true,
                Settings::USE_SORT      => true,
                Settings::GROUP_RIGHTS  => true,
            ]
        ];
    }

    /**
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected static function getTabs(): array
    {
        return [
            [
                'name'    => 'first_tab',
                'label'   => 'First Tab',
                'default' => 'First tab description',
                'siteId'  => 's1',
                'inputs'  => [
                    [
                        'type'  => Header::getType(),
                        'label' => 'First tab header',
                        'sort'  => 100,
                    ],
                    [
                        'type'        => Text::getType(),
                        'name'        => 'text',
                        'label'       => 'text input (maxlength 50)',
                        'default'     => '',
                        'maxLength'   => 50,
                        'sort'        => '200',
                        'placeholder' => 'enter a text here',
                        'disabled'    => true
                    ],
                    [
                        'type'        => Text::getType(),
                        'preInput'    => 'https://',
                        'postInput'   => '.domain.com',
                        'name'        => 'subdimain',
                        'label'       => 'Please, enter you subdomain here',
                        'help'        => 'format: https://<strong><u>subdomain</u></strong>.domain.com',
                        'default'     => 'subdomain',
                        'maxLength'   => 255,
                        'size'        => 10,
                        'sort'        => '250',
                        'placeholder' => 'subdomain'
                    ],
                    [
                        'type'        => Password::getType(),
                        'name'        => 'password',
                        'label'       => 'Please, enter password here',
                        'default'     => 'qwerty',
                        'maxLength'   => 255,
                        'sort'        => '260',
                        'placeholder' => 'enter password here'
                    ],
                    [
                        'type'        => Number::getType(),
                        'name'        => 'number',
                        'label'       => 'number input (min 0, max 100)',
                        'default'     => 50,
                        'max'         => 100,
                        'min'         => 0,
                        'sort'        => '300',
                        'placeholder' => 'enter a number here'
                    ],
                    [
                        'type'        => Textarea::getType(),
                        'name'        => self::OPTION__TEXTAREA,
                        'label'       => 'input textarea (3 rows 20 cols)',
                        'default'     => 'default text',
                        'rows'        => 3,
                        'cols'        => 20,
                        'help'        => 'textarea help',
                        'sort'        => '500',
                        'placeholder' => 'enter a big text here'
                    ],
                    [
                        'type'         => Textarea::getType(),
                        'name'         => 'input_textarea_2',
                        'label'        => 'Visual Editor',
                        'default'      => 'Visual Editor default text',
                        'help'         => 'visual editor help',
                        'sort'         => '550',
                        'htmlEditor'   => true,
                        'htmlEditorBB' => true,
                        'placeholder'  => 'enter a big text with bb-codes here'
                    ],
                    [
                        'type'    => Clock::getType(),
                        'name'    => 'clock',
                        'label'   => 'This is a clock',
                        'default' => '15:15',
                        'sort'    => 400
                    ],
                    [
                        'type'   => Row::getType(),
                        'inputs' => [
                            [
                                'type'    => Selectbox::getType(),
                                'name'    => 'selectbox-row-1',
                                'label'   => 'Mapping selectbox',
                                'options' => [
                                    '0' => 'option 0',
                                    '1' => 'option 1',
                                    '2' => 'option 2'
                                ]
                            ],
                            [
                                'type'    => Selectbox::getType(),
                                'name'    => 'selectbox-row-2',
                                'label'   => ' = ',
                                'options' => [
                                    '3' => 'option 3',
                                    '4' => 'option 4',
                                    '5' => 'option 5'
                                ]
                            ],
                        ]
                    ],
                    [
                        'type'    => SubTabControl::getType(),
                        'name'    => 'subtabcontrol',
                        'label'   => 'subtabcontrol label',
                        'default' => 'subtabcontrol default',
                        'sort'    => 550,
                        'subTabs' => [
                            [
                                'type'    => SubTab::getType(),
                                'name'    => 'subtab_1',
                                'label'   => 'subtab 1 label',
                                'default' => 'subtab 1 default',
                                'inputs'  => [
                                    [
                                        'type'    => Color::getType(),
                                        'name'    => 'color',
                                        'label'   => 'This is a color',
                                        'default' => '15:15',
                                        'help'    => 'Please, select a color',
                                        'sort'    => '600',

                                    ],
                                    [
                                        'type'    => Checkbox::getType(),
                                        'name'    => 'checkbox',
                                        'label'   => 'This is a checkbox',
                                        'default' => '#FFFF00',
                                        'help'    => 'Please, check me!',
                                        'sort'    => '700'
                                    ],
                                ]
                            ],
                            [
                                'type'    => SubTab::getType(),
                                'name'    => 'subtab_2',
                                'label'   => 'subtab 2 label',
                                'default' => 'subtab 2 default',
                                'inputs'  => [
                                    [
                                        'type'    => File::getType(),
                                        'name'    => 'file',
                                        'label'   => 'This is a file',
                                        'default' => '',
                                        'help'    => 'You may load an image here (max size 1 M)',
                                        'maxSize' => 1024 * 1024,
                                        'isImage' => true,
                                        'sort'    => '800'
                                    ],
                                    [
                                        'type'   => Iblock::getType(),
                                        'name'   => 'iblock',
                                        'label'  => 'This is a hidden single iblock',
                                        'sort'   => '900',
                                        'hidden' => true
                                    ],
                                    [
                                        'type'     => Iblock::getType(),
                                        'name'     => 'multiple_iblock',
                                        'multiple' => true,
                                        'label'    => 'This is a multiple iblock',
                                        'sort'     => '500'
                                    ],
                                ]
                            ]
                        ]
                    ],

                    [
                        'type'    => Addpreset::getType(),
                        'label'   => 'add preset s1',
                        'default' => 'new preset default name',
                        'popup'   => 'add preset popup', // false - not show
                        'sort'    => '1100'
                    ]
                ]
            ],
            [
                'name'    => 'presetTab',
                'label'   => 'Preset',
                'preset'  => true,
                'default' => 'This is a description of preset tab',
                'siteId'  => 's1',
                'inputs'  => [
                    [
                        'type'  => Header::getType(),
                        'label' => 'Preset header',
                    ],
                    [
                        'type'  => PresetName::getType(),
                        'name'  => 'presetName',
                        'label' => 'preset name',
                        'help'  => 'Enter a new name of preset here'
                    ],
                    [
                        'type'    => Color::getType(),
                        'name'    => self::OPTION__PRESET_COLOR,
                        'label'   => 'preset color',
                        'default' => '#FFAA00',
                        'help'    => 'color help',
                    ],
                    [
                        'type'  => Removepreset::getType(),
                        'label' => 'remove_preset',
                        'popup' => 'Are you sure?',
                    ]
                ],
            ],
            [
                'name'    => 'tab_22',
                'label'   => 'Second Tab',
                'default' => 'This is a description of second normal tab',
                'siteId'  => 'm',
                'inputs'  => [
                    [
                        'type'    => Selectbox::getType(),
                        'name'    => 'selectbox',
                        'label'   => 'single selectbox',
                        'options' => [
                            '0' => 'option 0',
                            '1' => 'option 1',
                            '2' => 'option 2'
                        ]
                    ],
                    [
                        'type'     => Selectbox::getType(),
                        'name'     => 'multiple_selectbox',
                        'label'    => 'Multiple selectbox',
                        'multiple' => true,
                        'options'  => [
                            '0' => 'option 0',
                            '1' => 'option 1',
                            '2' => 'option 2'
                        ]
                    ],
                    [
                        'type'    => Selectgroup::getType(),
                        'name'    => 'select_group',
                        'label'   => 'select_group',
                        'options' => [
                            'g1' => [
                                'name'    => 'Group 1',
                                'options' => [
                                    'g1:o1' => 'group 1: option 1',
                                    'g1:o2' => 'group 1: option 2',
                                    'g1:o3' => 'group 1: option 3',
                                ]
                            ],
                            'g2' => [
                                'name'    => 'Group 2',
                                'options' => [
                                    'g2:o1' => 'group 2: option 1',
                                    'g2:o2' => 'group 2: option 2',
                                    'g2:o3' => 'group 2: option 3',
                                ]
                            ],
                            'g3' => [
                                'name'    => 'Group 3',
                                'options' => [
                                    'g3:o1' => 'group 3: option 1',
                                    'g3:o2' => 'group 3: option 2',
                                    'g3:o3' => 'group 3: option 3',
                                ]
                            ]
                        ]
                    ],
                    [
                        'type'    => Radio::getType(),
                        'name'    => 'radio_demo',
                        'label'   => 'radio',
                        'options' => [
                            '1' => 'v1',
                            '2' => 'v2',
                            '3' => 'v3'
                        ]
                    ],
                    [
                        'type'        => Schedule::getType(),
                        'name'        => 'schedule_demo',
                        'label'       => 'demo schedule',
                        'periodLabel' => 'period label',
                        'width'       => 400,
                        'height'      => 300
                    ],
                    [
                        'type'    => Addpreset::getType(),
                        'label'   => 'add preset',
                        'default' => 'new preset default name',
                        'popup'   => 'add preset popup 2' // false - not show
                    ]
                ]
            ],
            [
                'name'    => 'presetTab2',
                'label'   => 'Preset2',
                'preset'  => true,
                'default' => 'This is a description of preset tab 2',
                'siteId'  => 'm',
                'inputs'  => [
                    [
                        'type'  => Header::getType(),
                        'label' => 'Preset header',
                    ],
                    [
                        'type'    => Color::getType(),
                        'name'    => 'preset_color_2',
                        'label'   => 'preset color',
                        'default' => '#FFAA00',
                        'help'    => 'color help',
                    ],
                    [
                        'type'  => Removepreset::getType(),
                        'label' => 'remove_preset',
                        'popup' => 'Are you sure?',
                    ]
                ],
            ],
        ];
    }

    /**
     * @param bool $reload
     * @return mixed
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTextareaValueS1(bool $reload = false)
    {
        return $this->getNormalValue(self::OPTION__TEXTAREA, 's1', $reload);
    }

    /**
     * @param      $presetId
     * @param bool $reload
     * @return mixed
     * @throws ArgumentNullException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getS1PresetColor($presetId, bool $reload = false)
    {
        return $this->getPresetValue(self::OPTION__PRESET_COLOR, $presetId, 's1', $reload);
    }
}