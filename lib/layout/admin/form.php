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

use Rover\Fadmin\Layout\Admin\Input\TabControl;
use \Rover\Fadmin\Layout\Form as FromAbstract;
use Bitrix\Main\Localization\Loc;
use \Rover\Fadmin\Options;

Loc::loadMessages(__FILE__);

/**
 * Class Form
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Form extends FromAbstract
{
    /** @var TabControl */
    protected $tabControlLayout;

    /**
     * Form constructor.
     *
     * @param Options $options
     * @param array   $params
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(Options $options, array $params = array())
    {
        parent::__construct($options, $params);

        $this->tabControlLayout = Input::build($this->options->getTabControl());

        if (empty($this->params['top_buttons']) || !is_array($this->params['top_buttons']))
            $this->params['top_buttons'] = array();
    }

    /**
     * @return Request|\Rover\Fadmin\Layout\Request
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getRequest()
    {
        if (is_null($this->request)) {
            global $Update, $Apply, $RestoreDefaults, $REQUEST_METHOD;

            $params = array(
                'active_tab'        => $this->tabControlLayout->getBxTabControl()->ActiveTabParam(),
                'request_method'    => $REQUEST_METHOD,
                'update'            => $Update,
                'apply'             => $Apply,
                'restore_defaults'  => $RestoreDefaults
            );

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
    protected function showButtons()
    {
        if (!count($this->params['top_buttons']))
            return;

        $context = new \CAdminContextMenu($this->params['top_buttons']);
        $context->Show();
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function draw()
    {
        $this->tabControlLayout->draw();
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function show()
    {
        $this->getRequest()->process();
        $this->showMessages();
        $this->showButtons();
        $this->draw();
    }
}