<?php

namespace App\Models\User;

use Core\Crud\Insert;

class Signup extends Insert
{
    protected $table = 'user';

    public function userSignup(array $formData)
    {
        return $this->insert($formData)->getResult();
    }
}
