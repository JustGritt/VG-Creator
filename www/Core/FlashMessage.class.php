<?php

namespace App\Core;

class FlashMessage
{
    protected const FLASH_MESSAGE_KEY = 'flash_message';

    public function __construct()
    {

        $flash_messages = $_SESSION[self::FLASH_MESSAGE_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            $flash_message['remove'] = true;
        }

        $_SESSION[self::FLASH_MESSAGE_KEY] = $flash_messages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_MESSAGE_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
      return $_SESSION[self::FLASH_MESSAGE_KEY][$key]['value'] ?? false;
    }


    public function __destruct()
    {
        $flash_messages = $_SESSION[self::FLASH_MESSAGE_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            if ($flash_message['remove']) {
                unset($flash_messages[$key]);
           }
        }

        $_SESSION[self::FLASH_MESSAGE_KEY] = $flash_messages;
    }

}