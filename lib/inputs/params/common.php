<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 15:00
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Inputs\Params;
use Rover\Fadmin\Inputs\Input;
use Rover\Fadmin\Options;
/**
 * Trait MaxLength
 *
 * @package Rover\Fadmin\Inputs\Params
 */
trait Common
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var string|array */
    protected $value;

    /** @var string|array */
    protected $default;

    /** @var bool */
    protected $multiple = false;

    /** @var string */
    protected $help;

    /** @var string */
    protected $siteId;

    /** @var int|null */
    protected $presetId;

    /** @var int */
    protected $sort = 500;

    /** @var bool */
    protected $hidden = false;

    /** @var bool */
    protected $disabled = false;

    /** @var Input|null */
    protected $parent;

    /** @var Input[] */
    protected $children;

    /** @var Options */
    protected $optionsEngine;

    /**
     * @param $display
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated use setHidden()
     */
    public function setDisplay($display)
    {
        $this->hidden = !(bool)$display;

        return $this;
    }

    /**
     * @param $hidden
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setHidden($hidden)
    {
        $this->hidden = (bool)$hidden;

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated use isHidden()
     */
    public function getDisplay()
    {
        return !$this->hidden;
    }


    /**
     * @return int
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param $sort
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setSort($sort)
    {
        $this->sort = intval($sort);

        return $this;
    }

    /**
     * @return int
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param $siteId
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        $childrenCnt = count($this->children);
        if (!$childrenCnt)
            return $this;

        for ($i = 0; $i < $childrenCnt; ++$i){
            $child = $this->children[$i];
            $child->setSiteId($siteId);
        }

        return $this;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setLabel($label)
    {
        $this->label = trim($label);

        return $this;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setDefault($default)
    {
        if (is_array($default))
            $default = serialize($default);

        $this->default = trim($default);

        return $this;
    }

    /**
     * @return int|null
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getPresetId()
    {
        return $this->presetId;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isPreset()
    {
        return (bool)$this->getPresetId();
    }

    /**
     * @param $presetId
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPresetId($presetId)
    {
        $this->presetId = $presetId;

        $childrenCnt = count($this->children);
        if (!$childrenCnt)
            return $this;

        for ($i = 0; $i < $childrenCnt; ++$i){
            $child = $this->children[$i];
            $child->setPresetId($presetId);
        }

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param $value
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setHelp($value)
    {
        $this->help = $value;

        return $this;
    }

    /**
     * @return string
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param $value
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setDisabled($value)
    {
        $this->disabled = (bool)$value;

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|Input
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Input $parent
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setParent(Input $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Options
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getOptionsEngine()
    {
        return $this->optionsEngine;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getModuleId()
    {
        return $this->getOptionsEngine()->getModuleId();
    }

    /**
     * @return Input[]
     */
    public function getChildren()
    {
        return $this->children;
    }


}