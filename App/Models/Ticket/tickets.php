<?php

namespace App\Models\Ticket;

use Core\Configs;
use Core\Crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\Paging;

class Tickets extends Select
{
    protected $table = 'ticket';
    use Where, OrderBy, Paging;

    public function index($userId)
    {
        return $this->select()->where(['userId', $userId])->orderBy('ticketId', 'DESC')->paging(Configs::perPage());
    }
}
