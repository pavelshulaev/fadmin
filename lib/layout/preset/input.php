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
    /** @var string */
    public static $type = 'Preset';

    /** @var InputAbstract */
    protected $adminInput;

    /**
     * Input constructor.
     *
     * @param InputEngine $input
     */
    public function __construct(InputEngine $input)
    {
        parent::__construct($input);

        $className          = get_called_class();
        $inputClassName     = str_replace('\\' . self::$type . '\\', '\\' . \Rover\Fadmin\Layout\Admin\Input::$type . '\\', $className);
        $this->adminInput   = new $inputClassName($input);
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function draw()
    {
        $this->showLabel();
        $this->showPreInput();
        $this->showInput();
        $this->showPostInput();
        $this->showHelp();
    }

    /**
     * @param bool $empty
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showLabel($empty = false)
    {
       if (!$empty) : ?>
            <label for="<?=$this->input->getFieldId()?>"><?=$this->input->getLabel()?>:</label>
        <?php endif;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showMultiLabel()
    {
        ?>
            <label for="<?=$this->input->getFieldId()?>"><?=$this->input->getLabel()?>:<br>
                <img src="/bitrix/images/main/mouse.gif" width="44" height="21" border="0" alt="">
            </label>
        <?php
    }

    /**
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function showHelp()
    {
        $help = trim($this->input->getHelp());

        if (strlen($help)):
            ?><br><small style="color: #777;"><?=$help?></small><?php
        endif;
    }


    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPreInput()
    {
        $preInput = $this->input->getPreInput();
        if (strlen($preInput)) echo $preInput;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showPostInput()
    {
        $postInput = $this->input->getPostInput();
        if (strlen($postInput)) echo $postInput;
    }
}