<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 11.01.2016
 * Time: 17:41
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\SystemException;
use Rover\Fadmin\Inputs\Params\Placeholder;
use Rover\Fadmin\Options;

/**
 * Class Textarea
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Textarea extends Input
{
    use Placeholder;

    protected int  $rows         = 3;
    protected int  $cols         = 50;
    protected bool $htmlEditor   = false;
    protected bool $htmlEditorBB = false;

    /**
     * Textarea constructor.
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

        if (isset($params['rows'])) {
            $this->setRows($params['rows']);
        }

        if (isset($params['cols'])) {
            $this->setCols($params['cols']);
        }

        if (isset($params['htmlEditor'])) {
            $this->setHtmlEditor($params['htmlEditor']);
        }

        if (isset($params['htmlEditorBB'])) {
            $this->setHtmlEditorBB($params['htmlEditorBB']);
        }

        if (isset($params['placeholder'])) {
            $this->setPlaceholder($params['placeholder']);
        }
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param $rows
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setRows($rows): static
    {
        $this->rows = htmlspecialcharsbx($rows);

        return $this;
    }

    /**
     * @return int
     */
    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * @param $cols
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setCols($cols): static
    {
        $this->cols = htmlspecialcharsbx($cols);

        return $this;
    }

    /**
     * @return bool
     */
    public function isHtmlEditor(): bool
    {
        return $this->htmlEditor;
    }

    /**
     * @param $htmlEditor
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setHtmlEditor($htmlEditor): static
    {
        $this->htmlEditor = (boolean)$htmlEditor;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHtmlEditorBB(): bool
    {
        return $this->htmlEditorBB;
    }

    /**
     * @param $htmlEditorBB
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setHtmlEditorBB($htmlEditorBB): static
    {
        $this->htmlEditorBB = (boolean)$htmlEditorBB;

        return $this;
    }


}