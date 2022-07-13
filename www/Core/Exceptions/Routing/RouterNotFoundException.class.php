<?php

namespace App\Core\Exceptions\Routing;

use App\Core\Routing\Router;
use App\Core\View;
use App\Helpers\Utils;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Throwable;

class RouterNotFoundException extends \Exception
{
    public $message = 'No routes founds';
    public $code    = 404;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        new View("404", "error", "/Errors");
        http_response_code(404);
        die();
    }


}