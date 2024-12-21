<?php

namespace App\Models;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;
use Core\Token;

class GetRoleId extends Select
{
    use Where, Limit;

    protected $table = 'user';

    public function getRoleId()
    {
        $userId = Token::getUserId();
        return $this->select()->where(['userId', $userId])->first();
    }
}
