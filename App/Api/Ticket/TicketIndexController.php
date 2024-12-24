<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Tickets;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\UserPanelMenu;
use Core\Token;
use Core\View;
use Exception;

class TicketIndexController extends Controller
{
    public function index()
    {
        try {
            $tickets = Tickets::getInstance()->index(Token::fetchValueFromPayload('data', 'userId'));
            $input = [
                'menu' => UserPanelMenu::getInstance()->panelMenuBuilder(),
                'tickets' => $tickets->result ?? null,
                'pagination' => $tickets->pagination ?? null
            ];
            if (is_numeric(end($this->params)))
                array_pop($this->params);
            View::render($this->params, $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'ticketErrorMessage');
        }
    }
}
