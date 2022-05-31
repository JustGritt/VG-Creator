<?php

namespace App\Core;


class CleanWords
{

    public static function lastname($lastname):string
    {
        return strtoupper(trim($lastname));
    }

    public static function getMethod():string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function cleanHTTPMethod($method):string
    {
        $body = [];
        if (self::getMethod() == 'post') {
           foreach ($_POST as $key => $value) {
               $body[$key] = filter_var(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
           }
        }
        if (self::getMethod() == 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }



}