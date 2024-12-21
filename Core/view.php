<?php

namespace Core;

use Core\Helpers\Helper;

define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR);
define('HEADER', VIEWS . 'Layout' . DIRECTORY_SEPARATOR . 'header.php');
define('FOOTER', VIEWS . 'Layout' . DIRECTORY_SEPARATOR . 'footer.php');

class View
{
    public static function render($page, $input = []): never
    {
        if ($input !== [])
            extract($input, EXTR_SKIP);
        if (is_array($page))
            $page = implode('/', $page);
        // if (!file_exists($views . "$page.min.php"))
        //     Helper::minifier($views . "$page.php");
        include VIEWS . "$page.php";
        // include $views . "$page.min.php";
        exit;
    }
}
