<?php

require_once('./App/Core/Patterns/Singleton.php');
require_once('./App/Core/Singletons/Database.php');
require_once('./App/config.php');

$database = App\Core\Singletons\Database::getInstance();
try {
    $seedFiles = scandir('./database/seeds');

    foreach($seedFiles as $file) {
        if ($file === '..' || $file === '.') continue;
        
        $seedName = str_replace('.sql', '', $file);

        $seed = file_get_contents('./database/seeds/' . $file);

        $database->execute($seed);

        echo "Seed $seedName ran successfully \n";
    }
} catch (\PDOException $e) {
    echo $e;
}