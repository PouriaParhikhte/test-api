<?php

namespace App\Models\Ticket;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;

class ViewTicket extends Select
{
    protected $table = 'ticket';
    use Where, Limit;

    public function fetch(int $ticketId, int $userId)
    {
        return $this->select()->where(['ticketId', $ticketId])->where(['userId', $userId])->first();
    }
}
