<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 17:11
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout;

use Rover\Fadmin\Options;

/**
 * Class FormAbstract
 *
 * @package Rover\Fadmin\Layout
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Form
{
    /** @var Options */
    protected $options;

    /** @var array */
    protected $params;

    /** @var Request */
    protected $request;

    /**
     * Form constructor.
     *
     * @param Options $options
     * @param array   $params
     */
    public function __construct(Options $options, array $params = array())
    {
        $this->options  = $options;
        $this->params   = $params;
    }

    /**
     * @param Request $request
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Request
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function showMessages()
    {
        $messages       = $this->options->message->get();
        $messagesCnt    = count($messages);

        if (!$messagesCnt)
            return;

        for ($i = 0; $i < $messagesCnt; ++$i)
        {
            $message = new \CAdminMessage($messages[$i]);
            echo $message->Show();
        }
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function show();

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function includeGroupRightsTab()
    {
        global $APPLICATION, $REQUEST_METHOD;

        $RIGHTS     = $_REQUEST['RIGHTS'];
        $SITES      = $_REQUEST['SITES'];
        $GROUPS     = $_REQUEST['GROUPS'];
        $Apply      = $_REQUEST['Apply'];
        $Update     = $_REQUEST['Update'] ?:$Apply;
        $module_id  = $_REQUEST['mid'];

        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    }
}