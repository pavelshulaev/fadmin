<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 17.11.2016
 * Time: 18:01
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Options;

/**
 * Class Message
 *
 * @package Rover\Fadmin\Engine
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Message
{
    const TYPE__OK    = 'OK';
    const TYPE__ERROR = 'ERROR';

    /**
     * message storage
     * @var array
     */
    protected array $messages = [];

    /**
     * @param        $message
     * @param string $type
     * @param bool $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function add($message, string $type = self::TYPE__OK, bool $html = true): void
    {
        if (is_array($message)) {
            $message = implode("\n", $message);
        }

        if (!$html) {
            $message = htmlspecialcharsbx($message);
        }

        $this->messages[] = [
            'MESSAGE' => trim($message),
            'HTML'    => $html,
            'TYPE'    => htmlspecialcharsbx($type),
        ];
    }

    /**
     * @param      $message
     * @param bool $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function addOk($message, bool $html = false): void
    {
        $this->add($message, self::TYPE__OK, $html);
    }

    /**
     * @param      $message
     * @param bool $html
     * @author Pavel Shulaev (http://rover-it.me)
     */
    public function addError($message, bool $html = false): void
    {
        $this->add($message, self::TYPE__ERROR, $html);
    }

    /**
     * @return array
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function get(): array
    {
        return $this->messages;
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function clear(): void
    {
        $this->messages = [];
    }

    /**
     * @author Pavel Shulaev (https://rover-it.me)
     */
    public function showAdmin(): void
    {
        $messages    = $this->get();
        $messagesCnt = count($messages);

        if (!$messagesCnt) {
            return;
        }

        for ($i = 0; $i < $messagesCnt; ++$i) {
            $message = new \CAdminMessage($messages[$i]);
            echo $message->Show();
        }
    }
}