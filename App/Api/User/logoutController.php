<?php

namespace App\Api\User;

use Core\Configs;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Token;
use Exception;

class LogoutController extends Controller
{
    public function logout()
    {
        try {
            Token::logout();
        } catch (Exception $exception) {
            Helper::redirectTo(Configs::homePageUrl(), $exception->getMessage(), $exception->getCode());
        }
    }
}
