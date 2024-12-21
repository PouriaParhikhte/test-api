<?php

namespace App\Models\Ticket;

use Core\Configs;
use Core\Crud\Select;
use Core\Helpers\Paging;

class Received extends Select
{
    use Paging;
    protected $table = 'ticket';

    public function tickets()
    {
        return $this->select()->paging(Configs::perPage());
    }
}
