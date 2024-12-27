<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 14.06.2018
 * Time: 7:59
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

/**
 * Class SubTab
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTab extends Input
{
    protected array $inputsConfig;

    /**
     * SubTab constructor.
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

        if (isset($params['inputs']) && is_array($params['inputs'])) {
            $this->inputsConfig = $params['inputs'];
        }
    }

    /**
     * @param bool $reload
     * @return array|Input[]
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

        return $this->children;
    }

    /**
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function loadInputs(): void
    {
        $this->children = [];
        $inputsCnt      = count($this->inputsConfig);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $this->children[] = self::build($this->inputsConfig[$i], $this->optionsEngine, $this);
        }
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

    /**
     * @return bool
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest(): bool
    {
        $inputs    = $this->getInputs();
        $inputsCnt = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $input = $inputs[$i];
            $input->setValueFromRequest();
        }

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
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear(): void
    {
        $inputs    = $this->getInputs();
        $inputsCnt = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            $input = $inputs[$i];
            $input->clear();
        }
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
}