<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.09.2017
 * Time: 15:05
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Inputs\Removepreset;
use Rover\Fadmin\Options;
use Rover\Fadmin\Options\Event;

/**
 * Class Request
 *
 * @package Rover\Fadmin\Layout
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Request
{
    protected Options     $options;
    protected array       $params;
    protected HttpRequest $request;

    /**
     * Request constructor.
     *
     * @param Options $options
     * @param array $params
     */
    public function __construct(Options $options, array $params = [])
    {
        $this->options = $options;
        $this->params  = $params;
        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function process(): void
    {
        // action before
        if (!$this->options->event->handle(Event::BEFORE_GET_REQUEST)->isSuccess()) {
            return;
        }

        if ($this->request->get(Addpreset::getType())) {
            $this->addPreset();
        } elseif ($this->request->get(Removepreset::getType())) {
            $this->removePreset();
        } else {
            $this->setValues();
        }
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    abstract function setValues();

    /**
     * @return int
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function addPreset()
    {
        [$siteId, $value] = explode(Addpreset::SEPARATOR,
            $this->request->get(Addpreset::getType()));

        return intval($this->options->getPreset()->add($value, $siteId));
    }

    /**
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function removePreset()
    {
        [$siteId, $id] = explode(Removepreset::SEPARATOR,
            $this->request->get(Removepreset::getType()));

        return $this->options->getPreset()->remove($id, $siteId);
    }

    /**
     * @throws ArgumentNullException
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function restoreDefaults(): void
    {
        Option::delete($this->options->getModuleId());
    }

    /**
     * @param $url
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function redirect($url): void
    {
        if (!$this->options->event
            ->handle(Event::BEFORE_REDIRECT_AFTER_REQUEST, compact('url'))
            ->isSuccess()) {
            return;
        }

        $url = $this->options->event->getParameter('url');

        if (empty($url)) {
            return;
        }

        LocalRedirect($url);
    }
}