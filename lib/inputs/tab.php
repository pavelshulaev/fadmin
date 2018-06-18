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

use Rover\Fadmin\Options;

/**
 * Class Tab
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (https://rover-it.me)
 */
class Tab extends Input
{
    /** @var Input[] */
    protected $inputs;

    /** @var array|mixed  */
    protected $inputsConfig = array();

    /**
     * Tab constructor.
     *
     * @param array   $params
     * @param Options $options
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function __construct(array $params, Options $options)
    {
        parent::__construct($params, $options);

        if (isset($params['inputs']) && count($params['inputs']))
            $this->inputsConfig = $params['inputs'];
    }

    /**
     * @param bool $reload
     * @return array
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getInputs($reload = false)
    {
        if (!count($this->inputs) || $reload)
        {
            $this->inputs   = array();
            $inputsCnt      = count($this->inputsConfig);

            for ($i = 0; $i < $inputsCnt; ++$i){
                $inputParams = $this->inputsConfig[$i];
                if (!isset($inputParams['siteId']))
                    $inputParams['siteId'] = $this->getSiteId();

                if (!isset($inputParams['presetId']))
                    $inputParams['presetId'] = $this->getPresetId();

                $this->inputs[] = self::factory($inputParams, $this->optionsEngine);
            }
        }

        // @TODO: after get tab inputs event
        return $this->inputs;
    }

    /**
     * @param array $filter
     * @return null|Input
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function search(array $filter)
    {
        $inputs     = $this->getInputs();
        $inputsCnt  = count($inputs);

        // @TODO: search in subtabs
        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var Input $input */
            $input = $inputs[$i];

            if (isset($filter['id']) && strlen($filter['id'])
                && $filter['id'] == $input->getValueId())
                return $input;

            if (isset($filter['name']) && strlen($filter['name'])
                && $filter['name'] == $input->getValueName())
                return $input;
        }

        return null;
    }

    /**
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     * @TODO: realize in subtabs
     */
    public function __clone()
    {
        $inputs     = $this->getInputs();
        $newInputs  = array();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var Input $input */
            $input      = $inputs[$i];
            $newInputs[]= clone $input;
        }

        $this->setInputs($newInputs);
    }


    /**
     * @param array $inputs
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setInputs(array $inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear()
    {
        $inputs     = $this->getInputs();
        $inputsCnt  = count($inputs);

        for ($i = 0; $i < $inputsCnt; ++$i) {
            /** @var Input $input */
            $input = $inputs[$i];
            $input->clear(); // @TODO: realize in subtabs
        }
    }
}