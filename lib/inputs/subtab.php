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

use Rover\Fadmin\Tab;

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

    /**
     * SubTab constructor.
     *
     * @param array $params
     * @param Tab   $tab
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(array $params, Tab $tab)
    {
        parent::__construct($params, $tab);

        if (isset($params['inputs']) && is_array($params['inputs'])){
            $inputsCnt = count($params['inputs']);
            for ($i = 0; $i < $inputsCnt; ++$i)
                $this->inputs[] = self::factory($params['inputs'][$i], $tab);
        }
    }

    /**
     * @return array|mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputs()
    {
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
}