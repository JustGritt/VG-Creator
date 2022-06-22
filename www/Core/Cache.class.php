<?php

namespace App\Core;

class Cache
{

    //create a cache Etag
    public static function createEtag($data)
    {
        return md5($data);
    }

    //create the cache
    public static function createCache($data, $etag)
    {
        header("Cache-Control: max-age=0, must-revalidate");
        header("Etag: " . $etag);
        echo $data;
    }

    //check if the cache is valid
    public static function checkCache($etag)
    {
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
    }


}