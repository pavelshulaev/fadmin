<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 18:30
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Options;

/**
 * Class Submit
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Submit extends Input
{
    protected string $popup = '';

    /**
     * Submit constructor.
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

        if (isset($params['popup'])) {
            $this->popup = $params['popup'];
        }
    }

    /**
     * @param $value
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function beforeSaveValue(&$value): bool
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
     * @param $value
     * @return void
     * @author Pavel Shulaev (https://rover-it.me)
     * @internal
     */
    protected function afterLoadValue(&$value): void
    {
        $value = $this->default;
    }

    /**
     * @return string
     */
    public function getPopup(): string
    {
        return $this->popup;
    }

    /**
     * @param $popup
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPopup($popup): static
    {
        $this->popup = $popup;

        return $this;
    }
}