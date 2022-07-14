<?php

namespace App\Helpers;

use App\Core\Exceptions\Routing\RouterException;
use App\Core\Routing\Router;


class Utils
{
    public static function  truncate($text, $chars = 25)
    {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text . " ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }

    /**
     */
    public static function redirect($where = null): void
    {
        if (isset($where)) {
            try {
                header('Location: /' . Router::getInstance()->url($where));
            } catch (RouterException $e) {
                header('Location: /' . $where);
            }
            Exit();
        }
    }

    /**
     * Abort request and set status code
     *
     * @param int $code
     * @return void
     */
    public static function abort(int $code):void
    {
        header("HTTP/1.1 ".$code);
    }

    /**
     * Get DB name of a model class
     *
     * @param object $class
     * @return string
     */
    public static function getDBNameFromClass(object $class):string
    {
        $reflect = new \ReflectionClass($class);
        return DBPREFIXE.strtolower($reflect->getShortName());
    }
}
