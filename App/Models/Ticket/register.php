<?php

namespace App\Models\Ticket;

use Core\Crud\Insert;

class Register extends Insert
{
    protected $table = 'ticket';

    public function ticket(array $formData)
    {
        return $this->insert($formData)->getResult();
    }
}
