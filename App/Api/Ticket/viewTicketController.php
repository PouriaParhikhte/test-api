<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\ViewTicket;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\UserPanelMenu;
use Core\Token;
use Core\View;
use Exception;

class ViewTicketController extends Controller
{
    public function index()
    {
        try {
            $input = [
                'menu' => UserPanelMenu::getInstance()->panelMenuBuilder(),
                'ticket' => ViewTicket::getInstance()->fetch(end($this->params), Token::fetchValueFromPayload('data', 'userId')) ?? Helper::notFound()
            ];
            array_pop($this->params);
            View::render($this->params, $input);
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }
}
