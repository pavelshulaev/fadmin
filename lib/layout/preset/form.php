<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 17:13
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Custom;
use Rover\Fadmin\Inputs\Header;
use Rover\Fadmin\Inputs\Label;
use \Rover\Fadmin\Layout\Form as FromAbstract;
use \Rover\Fadmin\Inputs\SubTabControl;
use \Rover\Fadmin\Options;
use Rover\Fadmin\Inputs\Tab;
use \Rover\Fadmin\Inputs\Input;

/**
 * Class Form
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Form extends FromAbstract
{
    /**
     * Form constructor.
     *
     * @param Options $options
     * @param array $params
     * @throws ArgumentNullException
     */
    public function __construct(Options $options, array $params = [])
    {
        parent::__construct($options, $params);

        if (empty($this->params['preset_id'])) {
            throw new ArgumentNullException('preset_id');
        }

        $this->params['preset_id'] = intval($this->params['preset_id']);

        $this->params['form_id'] = isset($this->params['form_id'])
            ? trim($this->params['form_id'])
            : '';

        $this->params['back_url'] = isset($this->params['back_url'])
            ? trim($this->params['back_url'])
            : '';

        $this->params['this_url'] = isset($this->params['this_url'])
            ? trim($this->params['this_url'])
            : '';

        $this->params['custom_buttons'] = isset($this->params['custom_buttons'])
            ? trim($this->params['custom_buttons'])
            : '';

        if (empty($this->params['buttons']) || !is_array($this->params['buttons'])) {
            $this->params['buttons'] = [];
        }
    }

    /**
     * @return
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = new Request($this->options, [
                'back_url'  => $this->params['back_url'],
                'this_url'  => $this->params['this_url'],
                'preset_id' => $this->params['preset_id'],
            ]);
        }

        return $this->request;
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function show(): void
    {
        $this->getRequest()->process();
        $this->options->message->showAdmin();
        $this->draw();
    }

    /**
     * @param Tab $tab
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getData(Tab $tab): array
    {
        if (!$tab->isPreset()) {
            throw new ArgumentOutOfRangeException('tab');
        }

        $data   = [];
        $inputs = $tab->getInputs();

        foreach ($inputs as $input) {
            $data[$input->getFieldName()] = $input->getValue();
        }

        return $data;
    }

    /**
     * @param Tab $tab
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getFormTabs(Tab $tab): array
    {
        if (!$tab->isPreset()) {
            throw new ArgumentOutOfRangeException('tab');
        }

        $formTabs = [];
        $formTab  = [];
        $inputs   = $tab->getInputs();

        foreach ($inputs as $input) {
            if ($input::getType() == Header::getType()) {
                if (!empty($formTab)) {
                    $formTabs[] = $formTab;
                }

                $formTab = [
                    'id'     => (count($formTabs) + 1) . '_' . $input->getFieldName(),
                    'name'   => $input->getLabel(),
                    //'title'  => $input->getLabel(),
                    'title'  => $input->getHelp(),
                    'fields' => []
                ];

                continue;
            }

            $field = [
                'id'   => $input->getFieldId(),
                'name' => strip_tags($input->getLabel()),
                'type' => $this->getType($input),
            ];

            // if ($field['type'] == 'custom')
            $field['value'] = $this->getValue($input);

            $formTab['fields'][] = $field;
        }

        $formTab['fields'][] = [
            'type'  => 'custom',
            'id'    => 'preset_id',
            'value' => '<input type="hidden" name="' . Request::INPUT__FORM_ID . '" value="' . $this->params['preset_id'] . '">'
        ];

        if (!empty($formTab)) {
            $formTabs[] = $formTab;
        }

        return $formTabs;
    }

    /**
     * @param Input $input
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getType(Input $input): string
    {
        return match ($input->getType()) {
            Label::getType()  => 'label',
            Custom::getType() => 'section',
            default           => 'custom',
        };
    }

    /**
     * @param Input $input
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function getValue(Input $input): string
    {
        /** @var \Rover\Fadmin\Layout\Preset\Input $layout */
        $layout = \Rover\Fadmin\Layout\Preset\Input::build($input);

        ob_start();

        $layout->showPreInput();
        $layout->showInput();
        $layout->showPostInput();
        $layout->showHelp();

        return ob_get_clean();
    }

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw(): void
    {
        $tab      = $this->options->getTabControl()->getTabByPresetId($this->params['preset_id']);
        $formTabs = $this->getFormTabs($tab);
        $data     = $this->getData($tab);

        global $APPLICATION;
        $APPLICATION->IncludeComponent(
            "bitrix:main.interface.form",
            "",
            [
                "FORM_ID" => $this->params['form_id'],   //идентификатор формы
                "TABS"    => $formTabs,                  //описание вкладок формы
                "BUTTONS" => [                      //кнопки формы, возможны кастомные кнопки в виде html в "custom_html"
                    "back_url"         => $this->params['back_url'],
                    "custom_html"      => $this->params['custom_buttons'],
                    "standard_buttons" => true
                ],
                "DATA"    => $data,   //данные для редактировани
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );
    }
}