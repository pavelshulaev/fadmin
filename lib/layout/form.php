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
    protected Options $options;
    protected array $params;
    protected Request $request;

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
    public function setRequest(Request $request): Form
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
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract public function show();

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public static function includeGroupRightsTab(): void
    {
        $RIGHTS     = $_REQUEST['RIGHTS'];
        $SITES      = $_REQUEST['SITES'];
        $GROUPS     = $_REQUEST['GROUPS'];
        $Apply      = $_REQUEST['Apply'];
        $Update     = $_REQUEST['Update'] ?:$Apply;
        $module_id  = $_REQUEST['mid'];

        global $APPLICATION, $REQUEST_METHOD;
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    }
}