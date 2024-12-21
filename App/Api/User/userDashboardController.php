<?php

namespace App\Api\User;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\UserPanelMenu;
use Core\View;
use Exception;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $input = [
                'menu' => UserPanelMenu::getInstance()->panelMenuBuilder()
            ];
            View::render($this->params, $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
