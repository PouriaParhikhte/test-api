<?php

namespace App\Api\Panel;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Token;
use Core\View;
use Exception;

class ManagerController extends Controller
{
    public function index()
    {
        try {
            $page = (Token::fetchValueFromPayload('data', 'userId') !== 0 && Token::fetchValueFromPayload('data', 'roleId') === 1) ? 'api/panel/dashboard' : 'api/panel/manager';
            View::render($page);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
