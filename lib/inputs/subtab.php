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

use Rover\Fadmin\Options;

/**
 * Class SubTab
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class SubTab extends Input
{
    /** @var array  */
    protected $inputsConfig = array();

    /**
     * SubTab constructor.
     *
     * @param array      $params
     * @param Options    $options
     * @param Input|null $parent
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Options $options, Input $parent = null)
    {
        parent::__construct($params, $options, $parent);

        if (isset($params['inputs']) && is_array($params['inputs']))
            $this->inputsConfig = $params['inputs'];
    }

    /**
     * @param bool $reload
     * @return array|Input[]
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputs($reload = false)
    {
        return $this->getChildren($reload);
    }

    /**
     * @param bool $reload
     * @return Input[]
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getChildren($reload = false)
    {
        if (is_null($this->children) || $reload)
            $this->loadInputs();

        return $this->children;
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    protected function loadInputs()
    {
        $this->children = array();
        $inputsCnt      = count($this->inputsConfig);

        for ($i = 0; $i < $inputsCnt; ++$i)
            $this->children[] = self::build($this->inputsConfig[$i], $this->optionsEngine, $this);
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    public function beforeSaveValue(&$value)
    {
        return false;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function beforeLoadValue()
    {
        return false;
    }

    /**
     * @return bool|void
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setValueFromRequest()
    {
        $inputs     = $this->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i){
            /** @var Input $input */
            $input = $inputs[$i];
            $input->setValueFromRequest();
        }
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function sort()
    {
        $inputs = $this->getInputs();

        if (!count($inputs))
            return;

        usort($inputs, function(Input $i1, Input $i2)
        {
            if($i1->getSort() < $i2->getSort()) return -1;
            elseif($i1->getSort() > $i2->getSort()) return 1;
            else return 0;
        });

        $this->children = $inputs;
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear()
    {
        $inputs     = $this->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i){
            /** @var Input $input */
            $input = $inputs[$i];
            $input->clear();
        }
    }

    /**
     * @param array $inputs
     * @author Pavel Shulaev (https://rover-it.me)
     * @deprecated
     */
    public function setInputs(array $inputs)
    {
        $this->children = $inputs;
    }
}