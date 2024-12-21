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
            $page = (Token::getUserId() !== 0 && Token::getRoleId() === 1) ? 'api/panel/dashboard' : 'api/panel/manager';
            View::render($page);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
