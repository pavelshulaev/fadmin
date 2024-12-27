<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.09.2017
 * Time: 15:07
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Addpreset;
use Rover\Fadmin\Layout\Request as RequestAbstract;
use Rover\Fadmin\Options;
use Rover\Fadmin\Inputs\Tab;

/**
 * Class Request
 *
 * @package Rover\Fadmin\Layout\Admin
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Request extends RequestAbstract
{
    protected string $moduleId;
    protected string $activeTab;
    protected string $requestMethod = 'POST';
    protected bool   $update;
    protected bool   $apply;
    protected bool   $restoreDefaults;

    /**
     * Request constructor.
     *
     * @param Options $options
     * @param array $params
     * @throws SystemException
     */
    public function __construct(Options $options, array $params = [])
    {
        parent::__construct($options, $params);

        $this->moduleId = htmlspecialcharsbx($this->options->getModuleId());

        if (!empty($this->params['request_method'])) {
            $this->requestMethod = htmlspecialcharsbx($this->params['request_method']);
        }

        $this->activeTab = isset($this->params['active_tab'])
            ? trim($this->params['active_tab'])
            : null;

        $this->update = isset($this->params['update']) && $this->params['update'];

        $this->apply = isset($this->params['apply']) && $this->params['apply'];

        $this->restoreDefaults = isset($this->params['restore_defaults']) && $this->params['restore_defaults'];
    }

    /**
     * @return void
     * @throws SystemException
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValues(): void
    {
        if (!$this->check()) {
            return;
        }

        if (strlen($this->restoreDefaults) > 0) {
            $this->restoreDefaults();
        } else {
            try {
                if ($this->options->getTabControl()->setValueFromRequest(true)) {
                    $this->redirect();
                }
            } catch (\Exception $e) {
                $this->options->handleException($e);
            }
        }
    }

    /**
     * @param string|null $activeTab
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function redirect($activeTab = null): void
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if (strlen($this->update) && strlen($request["back_url_settings"])) {
            parent::redirect($request["back_url_settings"]);
        }

        $activeTab = $activeTab
            ? 'tabControl_active_tab=' . $activeTab
            : $this->activeTab;

        global $APPLICATION;

        parent::redirect($APPLICATION->GetCurPage()
            . "?mid=" . urlencode($this->moduleId)
            . "&lang=" . urlencode(LANGUAGE_ID)
            . "&back_url_settings=" . urlencode($request["back_url_settings"])
            . "&" . $activeTab);
    }

    /**
     * @return int
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function addPreset()
    {
        [$siteId, $value] = explode(Addpreset::SEPARATOR,
            $this->request->get(Addpreset::getType()));

        $presetId = intval($this->options->getPreset()->add(urldecode($value), urldecode($siteId)));

        if ($presetId) {
            $presetTab = $this->options->getTabControl()->getTabByPresetId($presetId, $siteId, true);
            if (!$presetTab instanceof Tab) {
                throw new ArgumentOutOfRangeException('presetId');
            }

            $this->redirect($presetTab->getFieldName());
        }
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function removePreset(): void
    {
        try {
            if (parent::removePreset()) {
                $this->redirect();
            }
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     */
    protected function check(): bool
    {
        return (($this->requestMethod === 'POST')
            && (strlen($this->update . $this->apply . $this->restoreDefaults) > 0)
            && check_bitrix_sessid());
    }

    /**
     * @throws SystemException
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function restoreDefaults(): void
    {
        parent::restoreDefaults();
        $this->redirect();
    }
}