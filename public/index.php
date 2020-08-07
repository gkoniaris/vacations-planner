<?php

require_once('./autoloader.php');

// function exception_handler(\Throwable $exception) {
//     error_log($exception->getMessage());

//     echo '{ "error": "Something went wrong" }';
// }
  
// set_exception_handler('exception_handler');

$request = App\Core\Singletons\Request::getInstance();
$request->serve();
