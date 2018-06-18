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
    /** @var Input[] */
    protected $inputs;

    /** @var array  */
    protected $inputsConfig = array();

    /**
     * SubTab constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Options $options)
    {
        parent::__construct($params, $options);

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
        if (is_null($this->inputs) || $reload) {
            $this->inputs   = array();
            $inputsCnt      = count($this->inputsConfig);
            for ($i = 0; $i < $inputsCnt; ++$i)
                $this->inputs[] = self::factory($this->inputs[$i], $this->optionsEngine);
        }

        return $this->inputs;
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

        $this->setInputs($inputs);
    }

    /**
     * @param array $inputs
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setInputs(array $inputs)
    {
        $this->inputs = $inputs;
    }
}