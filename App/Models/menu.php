<?php

namespace App\Models;

use Core\menu\SiteMenu;

class Menu extends SiteMenu
{
    protected $table = 'url';

    public function menu()
    {
        return $this->siteMenuBuilder();
    }
}
