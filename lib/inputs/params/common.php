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
use Rover\Fadmin\Inputs\Tab;
use Rover\Fadmin\Options;

/**
 * Trait MaxLength
 *
 * @package Rover\Fadmin\Inputs\Params
 */
trait Common
{
    protected string $id;
    protected string $name;
    protected string $label;
    protected mixed  $value;
    protected mixed  $default;
    protected bool   $multiple  = false;
    protected string $help      = '';
    protected string $preInput  = '';
    protected string $postInput = '';
    protected string $siteId;
    protected int    $presetId;
    protected int    $sort      = 500;
    protected bool   $hidden    = false;
    protected bool   $disabled  = false;
    protected bool   $required  = false;
    protected Input  $parent;

    protected array   $children;
    protected Options $optionsEngine;

    /**
     * @param bool $display
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated use setHidden()
     */
    public function setDisplay(bool $display): static
    {
        $this->hidden = !$display;

        return $this;
    }

    /**
     * @param bool $hidden
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setHidden(bool $hidden): static
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated use isHidden()
     */
    public function getDisplay(): bool
    {
        return !$this->hidden;
    }


    /**
     * @return int
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setSort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string|null
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getSiteId(): ?string
    {
        if (!empty($this->siteId)) {
            return $this->siteId;
        }

        if ($this instanceof Tab) {
            return null;
        }

        $tab = $this->getTab();
        if ($tab instanceof Tab) {
            return $tab->getSiteId();
        }

        return null;
    }

    /**
     * @param string $siteId
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setSiteId(string $siteId): static
    {
        $siteId = trim($siteId);

        $this->siteId = $siteId;
        if (!isset($this->children)) {
            return $this;
        }

        $childrenCnt = count($this->children);
        if (!$childrenCnt) {
            return $this;
        }

        for ($i = 0; $i < $childrenCnt; ++$i) {
            $child = $this->children[$i];
            $child->setSiteId($siteId);
        }

        return $this;
    }

    /**
     * @return string
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setLabel(string $label): static
    {
        $this->label = trim($label);

        return $this;
    }

    /**
     * @return mixed
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getDefault(): mixed
    {
        return $this->default ?? null;
    }

    /**
     * @param $default
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setDefault($default): static
    {
        if (is_array($default)) {
            $default = serialize($default);
        }

        $this->default = trim($default);

        return $this;
    }

    /**
     * @return int|null
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getPresetId(): ?int
    {
        if (isset($this->presetId)) {
            return $this->presetId;
        }

        if ($this instanceof Tab) {
            return null;
        }

        $tab = $this->getTab();
        if ($tab instanceof Tab) {
            return $tab->getPresetId();
        }

        return null;
    }

    /**
     * @return null|Input
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getTab(): ?Input
    {
        $input = $this;
        do {
            $input = $input->getParent();
        } while ($input && ($input->getClassName() != Tab::getClassName()));

        return $input;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function isPreset(): bool
    {
        return (bool)$this->getPresetId();
    }

    /**
     * @param int $presetId
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setPresetId(int $presetId): static
    {
        $this->presetId = $presetId;
        if (!isset($this->children)) {
            return $this;
        }

        $childrenCnt = count($this->children);
        if (!$childrenCnt) {
            return $this;
        }

        for ($i = 0; $i < $childrenCnt; ++$i) {
            $child = $this->children[$i];
            $child->setPresetId($presetId);
        }

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }


    /**
     * @param $value
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setMultiple($value): static
    {
        $this->multiple = (bool)$value;

        return $this;
    }


    /**
     * @param string $value
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setHelp(string $value): static
    {
        $this->help = trim($value);

        return $this;
    }

    /**
     * @return string
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getHelp(): string
    {
        return $this->help;
    }

    /**
     * @param bool $value
     * @return $this
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function setDisabled(bool $value): static
    {
        $this->disabled = $value;

        return $this;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @return bool
     * @author Pavel Shulaev (http://rover-it.me)
     * @deprecated
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @return string
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|Input
     */
    public function getParent(): ?Input
    {
        return $this->parent ?? null;
    }

    /**
     * @param Input $parent
     * @return $this
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function setParent(Input $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Options
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getOptionsEngine(): Options
    {
        return $this->optionsEngine;
    }

    /**
     * @return string
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function getModuleId(): string
    {
        return $this->getOptionsEngine()->getModuleId();
    }

    /**
     * @return Input[]
     */
    public function getChildren(): array
    {
        return $this->children ?? [];
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getPreInput(): string
    {
        return $this->preInput;
    }

    /**
     * @param string $preInput
     * @return Common
     */
    public function setPreInput(string $preInput): static
    {
        $this->preInput = trim($preInput);

        return $this;
    }

    /**
     * @return string
     */
    public function getPostInput(): string
    {
        return $this->postInput;
    }

    /**
     * @param string $postInput
     * @return Common
     */
    public function setPostInput(string $postInput): static
    {
        $this->postInput = trim($postInput);

        return $this;
    }

    /**
     * @param bool $required
     * @return Common
     */
    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }
}