<?php

namespace App\Models\Ticket;

use Core\Crud\Update;

class AnswerTicket extends Update
{
    protected $table = 'ticket';

    public function register(array $formData)
    {
        if ($this->update($formData)->where(['ticketId', $formData['ticketId']])->getResult()) {
            $this->update(['status' => '1'])->where(['ticketId', $formData['ticketId']])->getResult();
            return 1;
        }
    }
}
