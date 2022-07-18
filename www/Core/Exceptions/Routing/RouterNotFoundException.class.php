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


    public function __construct(string $message = null, int $code = 404, bool $show_view = true)
    {
        parent::__construct($message??$this->message , $code);
        http_response_code($code);
        if(!$show_view)  return;
        $this->message = $message;


        new View("404", "error", "/Errors");
        die();
    }
}