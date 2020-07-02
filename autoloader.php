<?php

require_once('./vendor/autoload.php');

spl_autoload_register(function ($class) {
    require_once('./' . str_replace('\\', '/', $class) . '.php');
});

require_once('./App/config.php');
require_once('./App/middlewares.php');
require_once('./App/routes.php');