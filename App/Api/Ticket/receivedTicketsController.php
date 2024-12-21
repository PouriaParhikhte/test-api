<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Received;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\AdminPanelMenu;
use Core\View;
use Exception;

class ReceivedTicketsController extends Controller
{
    public function index()
    {
        try {
            $tickets = Received::getInstance()->tickets();
            $input = [
                'menu' => AdminPanelMenu::getInstance()->panelMenuBuilder(),
                'tickets' => $tickets->result,
                'pagination' => $tickets->pagination ?? null
            ];
            if (is_numeric(end($this->params)))
                array_pop($this->params);
            View::render($this->params, $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
