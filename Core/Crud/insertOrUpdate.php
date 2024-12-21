<?php

namespace Core\Crud;

class InsertOrUpdate extends Insert
{
    public function insertOrUpdate(array $input)
    {
        $insert = $this->insert($input)->getResult();
        if (!$insert)
            Update::getInstance()($this->table)->update($input)->getResult();
        return $this;
    }
}
