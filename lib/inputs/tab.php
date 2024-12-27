<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.06.2018
 * Time: 9:19
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

/**
 * Class Tab
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tab extends \Rover\Fadmin\Tab
{
    protected array $inputsConfig;
    protected bool  $isPreset;

    /**
     * Tab constructor.
     *
     * @param array $params
     * @param Options $options
     * @param Input|null $parent
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        parent::__construct($params, $options, $parent);

        $this->isPreset = isset($params['preset']) && $params['preset'];

        if (isset($params['inputs']) && count($params['inputs'])) {
            $this->inputsConfig = $params['inputs'];
        }

        if (isset($params['description'])) {
            $this->setDefault($params['description']);
        }
    }

    /**
     * @param bool $reload
     * @return Input[]
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputs(bool $reload = false): array
    {
        return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return Input[]
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren(bool $reload = false): array
    {
        if (!isset($this->children) || $reload) {
            $this->loadInputs();
        }

        // @TODO: after get tab inputs event
        return $this->children;
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function loadInputs(): void
    {
        $this->children = [];
        $inputsCnt      = count($this->inputsConfig);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $inputParams = $this->inputsConfig[$i];
            if (!isset($inputParams['siteId'])) {
                $inputParams['siteId'] = $this->getSiteId();
            }

            if (!isset($inputParams['presetId'])) {
                $inputParams['presetId'] = $this->getPresetId();
            }

            $this->children[] = self::build($inputParams, $this->optionsEngine, $this);
        }
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function __clone()
    {
        $this->loadInputs();
        parent::__clone();
    }


    /**
     * @param array $inputs
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function setInputs(array $inputs): void
    {
        $this->children = $inputs;
    }

    /**
     * @param bool $reload
     * @throws ArgumentNullException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear(bool $reload = false): void
    {
        $inputs    = $this->getInputs($reload);
        $inputsCnt = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $input = $inputs[$i];
            $input->clear();
        }
    }

    /**
     * @throws ArgumentNullException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest(): bool
    {
        if (!$this->optionsEngine->event
            ->handle(Options\Event::BEFORE_ADD_VALUES_TO_TAB_FROM_REQUEST,
                ['tab' => $this])
            ->isSuccess()) {
            return false;
        }

        $inputs    = $this->getInputs();
        $inputsCnt = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $input = $inputs[$i];
            $input->setValueFromRequest();
        }

        return true;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function getDescription():string
    {
        return $this->getDefault();
    }

    /**
     * @param string $description
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function setDescription(string $description = ''): static
    {
        return $this->setDefault($description);
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isPreset(): bool
    {
        return $this->isPreset;
    }

    /**
     * @param Input $input
     * @return Input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function addInput(Input $input): Input
    {
        if ($this->isPreset()) {
            $input->setPresetId($this->getPresetId());
        }

        if ($this->getSiteId()) {
            $input->setSiteId($this->getSiteId());
        }

        $this->children[] = $input;

        return $input;
    }


    /**
     * @param array $input
     * @return Input
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function addInputArray(array $input): Input
    {
        if ($this->isPreset()) {
            $input['presetId'] = $this->getPresetId();
        }

        if ($this->getSiteId()) {
            $input['siteId'] = $this->getSiteId();
        }

        $input            = self::build($input, $this->optionsEngine, $this);
        $this->children[] = $input;

        return $input;
    }

    /**
     * @param string $inputName
     * @param bool $reload
     * @return array|null|string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputValue(string $inputName, bool $reload = false)
    {
        $input = $this->searchOneByName($inputName);

        if ($input instanceof Input) {
            return $input->getValue($reload);
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     * @throws ArgumentOutOfRangeException
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setInputValue($name, $value): bool
    {
        $input = $this->searchOneByName($name);

        if (!$input instanceof Input) {
            return false;
        }

        $input->setValue($value);

        return true;
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort(): void
    {
        $inputs = $this->getInputs();
        if (!count($inputs)) {
            return;
        }

        usort($inputs, function (Input $i1, Input $i2) {
            // sort on subtabs
            if (method_exists($i1, 'sort')) {
                $i1->sort();
            }

            if ($i1->getSort() < $i2->getSort()) {
                return -1;
            } elseif ($i1->getSort() > $i2->getSort()) {
                return 1;
            } else {
                return 0;
            }
        });

        $this->children = $inputs;
    }

    /**
     * @param bool $reload
     * @return null
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getPreset(bool $reload = false)
    {
        if (!$this->isPreset()) {
            throw new ArgumentOutOfRangeException('tab');
        }

        if (!$this->getPresetId()) {
            throw new ArgumentNullException('presetId');
        }

        return $this->optionsEngine->getPreset()->getById(
            $this->getPresetId(), $this->siteId, $reload);
    }

    /**
     * @param bool $reload
     * @return mixed|null
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getPresetName(bool $reload = false): ?string
    {
        $preset = $this->getPreset($reload);

        if (is_array($preset) && isset($preset['name'])) {
            return $preset['name'];
        }

        return null;
    }

    /**
     * @param $name
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws NotSupportedException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPresetName($name): void
    {
        if (!$this->isPreset()) {
            throw new NotSupportedException();
        }

        $name = trim($name);
        if (!strlen($name)) {
            throw new ArgumentNullException('name');
        }

        $this->optionsEngine->getPreset()->updateName(
            $this->getPresetId(),
            $name,
            $this->getSiteId()
        );
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value): bool
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue(): bool
    {
        return false;
    }
}