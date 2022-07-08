<?php

namespace App\Helpers;

use App\Core\Router;

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

    public static function redirect($where = null)
    {
        $router = null;

        if (isset($where)) {
            header('Location: ' . $where);
            Exit();
        } else {
            // $router = Routing::getInstance();
            // $router->redirect = "sd";
            // $router
            //$router->getName();
        }

       // return \App\Core\Routing::getInstance();
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
}
