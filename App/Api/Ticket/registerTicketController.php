<?php

namespace App\Api\Ticket;

header("Bearer: $_COOKIE[token]");

use App\Models\Ticket\Register;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Token;
use Core\Validation;
use Exception;

class RegisterTicketController extends Controller
{
    public function create(Validation $validation)
    {
        try {
            $validation->allRequired($this->params);
            $this->params['userId'] = Token::decodePayload(Token::getPayload($_COOKIE['token']))->data->userId;
            unset($this->params['token']);
            if (!Register::getInstance()->ticket($this->params)) {
                $message = 'ثبت تیکت با خطا همراه شد';
                http_response_code(302);
            } else
                $message = 'تیکت با موفقیت ثبت شد';
            throw new Exception($message);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
