<?php

require_once('./autoloader.php');

$request = App\Singletons\Request::getInstance();
$request->serve();
