<?php

require_once('./App/Patterns/Singleton.php');
require_once('./App/Singletons/Database.php');
require_once('./App/config.php');

try {
    $seedFiles = scandir('./database/seeds');

    foreach($seedFiles as $file) {
        if ($file === '..' || $file === '.') continue;
        
        $seedName = str_replace('.sql', '', $file);

        $seed = file_get_contents('./database/seeds/' . $file);

        App\Singletons\Database::execute($seed);

        echo "Seed $seedName ran successfully \n";
    }
} catch (\PDOException $e) {
    echo $e;
}