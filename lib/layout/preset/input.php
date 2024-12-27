<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12.09.2017
 * Time: 14:08
 *
 * @author Pavel Shulaev (https://rover-it.me)
 */

namespace Rover\Fadmin\Layout\Preset;

use Bitrix\Main\ArgumentNullException;
use Rover\Fadmin\Layout\Input as InputAbstract;
use Rover\Fadmin\Inputs\Input as InputEngine;

/**
 * Class Admin
 *
 * @package Rover\Fadmin\Layout
 * @author  Pavel Shulaev (https://rover-it.me)
 */
abstract class Input extends InputAbstract
{
    public static string    $type = 'Preset';
    protected InputAbstract $adminInput;

    /**
     * Input constructor.
     *
     * @param InputEngine $input
     */
    public function __construct(InputEngine $input)
    {
        parent::__construct($input);

        $className        = get_called_class();
        $inputClassName   =
            str_replace('\\' . self::$type . '\\', '\\' . \Rover\Fadmin\Layout\Admin\Input::$type . '\\', $className);
        $this->adminInput = new $inputClassName($input);
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw(): void
    {
        $this->showLabel();
        $this->showPreInput();
        $this->showInput();
        $this->showPostInput();
        $this->showHelp();
    }

    /**
     * @param bool $empty
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel(bool $empty = false): void
    {
        if (!$empty) : ?>
            <label for="<?= $this->input->getFieldId() ?>"><?= $this->input->getLabel() ?>:</label>
        <?php endif;
    }

    /**
     * @throws ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showMultiLabel(): void
    {
        ?>
        <label for="<?= $this->input->getFieldId() ?>"><?= $this->input->getLabel() ?>:<br>
            <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
        </label>
        <?php
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function showHelp(): void
    {
        $help = trim($this->input->getHelp());

        if (strlen($help)):
            ?><br><small style="color: #777;"><?= $help ?></small><?php
        endif;
    }


    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPreInput(): void
    {
        $preInput = $this->input->getPreInput();
        if (strlen($preInput)) {
            echo $preInput;
        }
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPostInput(): void
    {
        $postInput = $this->input->getPostInput();
        if (strlen($postInput)) {
            echo $postInput;
        }
    }
}