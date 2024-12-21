<?php

namespace App\Controllers;

use Core\Helpers\Prototype;
use App\Models\Tables;
use Core\Configs;
use Core\Helpers\Helper;

class DatabaseController extends Prototype
{
    public function create()
    {
        $tables = Tables::getInstance();
        $methods = get_class_methods($tables);
        $tables->chooseDatabase(Configs::database());
        $index = array_search('chooseDatabase', $methods);
        array_walk($methods, function ($value, $key) use ($tables, $index) {
            if ($key === $index)
                Helper::redirect();
            call_user_func([$tables, $value]);
        });
    }
}
