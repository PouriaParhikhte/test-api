<?php

namespace Core;

use Core\Helpers\Helper;
use Core\Helpers\Prototype;

abstract class Controller extends Prototype
{
    protected $params;

    public function __construct(mixed $input = null)
    {
        $this->params = json_decode(file_get_contents('php://input'), 1) ?? $input;
        if (Helper::getRequestMethod())
            Token::generate();
    }

    public function __destruct()
    {
        $logs = Helper::log();
        error_log($logs);
    }
}
