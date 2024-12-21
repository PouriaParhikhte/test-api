<?php

namespace Core\Crud;

use Core\Model;

class Select extends Model
{
    public function select(array $columns = [])
    {
        $columns = !empty($columns) ? implode(',', $columns) : '*';
        $this->sql = "SELECT $columns FROM `$this->table`";
        return $this;
    }
}
