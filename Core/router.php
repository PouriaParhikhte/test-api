<?php

namespace Core;

use App\Api\Panel\ManagerController;
use App\Api\Panel\PanelDashboardController;
use App\Api\Panel\PanelLoginController;
use App\Api\Panel\PanelLogoutController;
use App\Api\Ticket\AnswerController;
use App\Api\Ticket\AnswerTicketController;
use App\Api\Ticket\CreateTicketController;
use App\Api\Ticket\ReceivedTicketsController;
use App\Api\Ticket\RegisterTicketController;
use App\Api\Ticket\TicketIndexController;
use App\Api\Ticket\ViewTicketController;
use App\Api\User\LoginController;
use App\Api\User\LogoutController;
use App\api\user\SignupController;
use App\Api\User\UserDashboardController;
use App\Controllers\DatabaseController;
use App\Controllers\MainController;
use Core\Helpers\Helper;

class Router extends Route
{
    public function __construct($url)
    {
        $url = trim($url, '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        match ($requestMethod) {
            'GET' => $this->getRequests($url),
            'POST' => $this->postRequests($url),
        };
    }

    private function getRequests($url): void
    {
        $components = $this->urlToArray($url, $id);
        match ($url) {
            'database/tables' => $this->isAdmin()->loadControllerAndAction(DatabaseController::class, 'create', $components),
            'api/user/login', 'api/user/dashboard' => $this->isUser()->loadControllerAndAction(UserDashboardController::class, 'dashboard', $components),
            'api/user/logout' => $this->isUser()->loadControllerAndAction(LogoutController::class, 'logout', $components),
            'api/ticket/create' => $this->isUser()->loadControllerAndAction(CreateTicketController::class, 'create', $components),
            "api/ticket/index$id" => $this->isUser()->loadControllerAndAction(TicketIndexController::class, 'index', $components),
            "api/ticket/received$id"  => $this->isAdmin()->loadControllerAndAction(ReceivedTicketsController::class, 'index', $components),
            "api/ticket/answer$id"  => $this->isAdmin()->loadControllerAndAction(AnswerController::class, 'create', $components),
            "api/ticket/view$id"  => $this->isUser()->loadControllerAndAction(ViewTicketController::class, 'index', $components),
            'api/panel/manager' => $this->loadControllerAndAction(ManagerController::class, 'index', $components),
            'api/panel/login', 'api/panel/dashboard' => $this->isAdmin()->loadControllerAndAction(PanelDashboardController::class, 'index', $components),
            'api/panel/logout' => $this->isAdmin()->loadControllerAndAction(PanelLogoutController::class, 'logout', $components),
            default => $this->loadControllerAndAction(MainController::class, 'index', $components)
        };
    }

    private function postRequests($url): void
    {
        match ($url) {
            'api/user/signup' => $this->verifyToken()->checkCsrfToken()->loadControllerAndAction(SignupController::class, 'create', $_POST),
            'api/user/login' => $this->loadControllerAndAction(LoginController::class, 'login', $_POST),
            'api/ticket/register' => $this->isUser()->loadControllerAndAction(RegisterTicketController::class, 'create', $_POST),
            'api/ticket/answerTicket' => $this->isAdmin()->verifyToken()->loadControllerAndAction(AnswerTicketController::class, 'create', $_POST),
            'api/panel/login' => $this->loadControllerAndAction(PanelLoginController::class, 'login', $_POST),
            default => Helper::notFound()
        };
    }

    private function urlToArray($url, &$id = null): array
    {
        $url = explode('/', $url);
        $id = is_numeric(end($url)) ? '/' . end($url) : null;
        return $url;
    }

    private function checkCsrfToken()
    {
        Token::checkCsrf();
        return $this;
    }

    private function isAdmin()
    {
        if (Token::fetchValueFromPayload('roleId') !== 1)
            Helper::invalidRequest();
        return $this;
    }

    private function isUser()
    {
        if (Token::fetchValueFromPayload('roleId') !== 2)
            Helper::invalidRequest();
        return $this;
    }

    private function verifyToken()
    {
        Token::verify();
        return $this;
    }
}
