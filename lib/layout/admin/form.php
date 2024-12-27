<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 17:13
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Layout\Admin\Input\TabControl;
use Rover\Fadmin\Layout\Form as FromAbstract;
use Bitrix\Main\Localization\Loc;
use Rover\Fadmin\Options;

Loc::loadMessages(__FILE__);

/**
 * Class Form
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Form extends FromAbstract
{
    protected TabControl $tabControlLayout;

    /**
     * Form constructor.
     *
     * @param Options $options
     * @param array $params
     * @throws ArgumentNullException
     * @throws SystemException
     */
    public function __construct(Options $options, array $params = [])
    {
        parent::__construct($options, $params);

        $this->tabControlLayout = Input::build($this->options->getTabControl());

        if (empty($this->params['top_buttons']) || !is_array($this->params['top_buttons'])) {
            $this->params['top_buttons'] = [];
        }
    }

    /**
     * @return Request
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getRequest(): Request
    {
        if (!isset($this->request)) {
            global $Update, $Apply, $RestoreDefaults, $REQUEST_METHOD;

            $params = [
                'active_tab'       => $this->tabControlLayout->getBxTabControl()->ActiveTabParam(),
                'request_method'   => $REQUEST_METHOD,
                'update'           => $Update,
                'apply'            => $Apply,
                'restore_defaults' => $RestoreDefaults
            ];

            $this->request = new Request($this->options, $params);
        }

        return $this->request;
    }


    /**
     *  [
     *      [
     *          'TEXT' => ...,
     *          'LINK' => ...,
     *          'TITLE' => ...
     *      ],
     *      [
     *          ...
     *      ],
     *      ...
     *  ]
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showButtons(): void
    {
        if (!count($this->params['top_buttons'])) {
            return;
        }

        $context = new \CAdminContextMenu($this->params['top_buttons']);
        $context->Show();
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function draw(): void
    {
        $this->tabControlLayout->draw();
    }

    /**
     * @return void
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function show(): void
    {
        $this->getRequest()->process();
        $this->options->message->showAdmin();
        $this->showButtons();
        $this->draw();
    }
}