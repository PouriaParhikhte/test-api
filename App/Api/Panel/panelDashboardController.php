<?php

namespace App\Api\Panel;

use App\Models\GetRoleId;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\AdminPanelMenu;
use Core\Token;
use Core\View;
use Exception;

class PanelDashboardController extends Controller
{
    public function index()
    {
        try {
            $input = [
                'menu' => AdminPanelMenu::getInstance()->panelMenuBuilder()
            ];
            View::render('Api/Panel/dashboard', $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
