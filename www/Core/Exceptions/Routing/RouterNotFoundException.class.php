<?php

namespace App\Core\Exceptions\Routing;

use App\Core\Routing\Router;
use App\Helpers\Utils;
use Throwable;

class RouterNotFoundException extends \Exception
{
    public $message = 'No routes founds';
    public $code    = 404;

}