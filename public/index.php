<?php

declare(strict_types=1);

use Core\Configs;
use Core\Router;

include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

Router::getInstance($_GET['url'] ?? Configs::homePageUrl());
