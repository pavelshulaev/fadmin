<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:33
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

/**
 * Class Selectbox
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Row extends Input
{
    protected array $inputs = [];

    /**
     * Row constructor.
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
        if (!isset($params['name'])) {
            $params['name'] = self::getType();
        }

        if (!isset($params['label'])) {
            $params['label'] = self::getType();
        }

        parent::__construct($params, $options, $parent);

        if (isset($params['inputs']) && is_array($params['inputs'])) {
            foreach ($params['inputs'] as $input) {
                $this->inputs[] = self::build($input, $options, $this);
            }
        }
    }

    /**
     * @param $value
     * @return bool
     * @throws SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeSaveValue(&$value): bool
    {
        foreach ($this->inputs as $input) {
            $input->setValueFromRequest();
        }

        return false;
    }

    /**
     * @return Input[]
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }
}