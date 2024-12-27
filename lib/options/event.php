<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 07.12.2016
 * Time: 2:53
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Options;

use Bitrix\Main\EventResult;
use Rover\Fadmin\Options;
use Bitrix\Main;

/**
 * Class Event
 *
 * @package Rover\Fadmin\Engine
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Event
{
    /** standart events */
    const BEFORE_GET_REQUEST                    = 'beforeGetRequest';
    const BEFORE_REDIRECT_AFTER_REQUEST         = 'beforeRedirectAfterRequest';
    const BEFORE_ADD_VALUES_FROM_REQUEST        = 'beforeAddValuesFromRequest';
    const BEFORE_ADD_VALUES_TO_TAB_FROM_REQUEST = 'beforeAddValuesToTabFromRequest';
    const AFTER_ADD_VALUES_FROM_REQUEST         = 'afterAddValuesFromRequest';
    const BEFORE_ADD_PRESET                     = 'beforeAddPreset';
    const AFTER_ADD_PRESET                      = 'afterAddPreset';
    const BEFORE_REMOVE_PRESET                  = 'beforeRemovePreset';
    const AFTER_REMOVE_PRESET                   = 'afterRemovePreset';
    const BEFORE_MAKE_PRESET_TAB                = 'beforeMakePresetTab';
    const AFTER_MAKE_PRESET_TAB                 = 'afterMakePresetTab';
    const BEFORE_GET_TAB_INFO                   = 'beforeGetTabInfo';
    const AFTER_GET_TABS                        = 'afterGetTabs';
    const BEFORE_SHOW_TAB                       = 'beforeShowTab';

    public Options  $options;
    protected bool  $success    = true;
    protected array $parameters = [];

    /**
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getParameter($key): mixed
    {
        $key = trim($key);
        if (!strlen($key)) {
            throw new Main\ArgumentNullException('key');
        }

        return $this->parameters[$key] ?? null;
    }

    /**
     * @param       $name
     * @param array $parameters
     * @return $this
     * @throws Main\ArgumentNullException
     * @throws Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function handle($name, array $parameters = []): static
    {
        $name = trim($name);
        if (empty($name)) {
            throw new Main\ArgumentNullException('name');
        }

        if ($this->run($name, $parameters)->isSuccess()
            && (false === $this->options->runEventOldStyle($name, $this->parameters))) {
            $this->success = false;
        }

        return $this;
    }

    /**
     * @param $eventName
     * @param $parameters
     * @return $this
     * @throws Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function run($eventName, $parameters): static
    {
        $eventName = trim($eventName);
        if (empty($eventName)) {
            throw new Main\ArgumentNullException('eventName');
        }

        $event = new Main\Event($this->options->getModuleId(), $eventName, $parameters);
        $event->send();

        $results          = $event->getResults();
        $resultsCount     = count($results);
        $this->success    = true;
        $this->parameters = $parameters;

        if ($resultsCount) {

            for ($i = 0; $i < $resultsCount; $i++) {

                $eventResult = $results[$i];

                switch ($eventResult->getType()):
                    case EventResult::ERROR:
                        $this->success    = false;
                        $this->parameters = $eventResult->getParameters();
                        break(2);
                    case EventResult::SUCCESS:
                        $this->parameters = $eventResult->getParameters();
                        break;
                    case EventResult::UNDEFINED:
                    default:
                        break;
                endswitch;
            }
        }

        return $this;
    }
}