<?php

require_once('./autoloader.php');

$request = App\Core\Singletons\Request::getInstance();
$request->serve();
