<?php

require_once('./App/Core/Patterns/Singleton.php');
require_once('./App/Core/Singletons/Database.php');
require_once('./App/config.php');

$database = App\Core\Singletons\Database::getInstance();
try {
    $database->create('CREATE table migrations(
        id INT(10) UNSIGNED AUTO_INCREMENT  PRIMARY KEY,
        migration VARCHAR(30),
        run TINYINT(0) DEFAULT 0
      );
    ');
} catch (\Exception $e) {
    // Ignore if table already exists
}

try {
    $migrations = selectAll('SELECT * FROM migrations');
    $migrationFiles = scandir('./database/migrations');

    foreach($migrationFiles as $file) {
        if ($file === '..' || $file === '.') continue;
        
        $migrationName = str_replace('.sql', '', $file);

        $migrationHasRun = array_filter($migrations, function($migration) use ($file, $migrationName) {
            return $migration['migration'] === $migrationName && $migration['run'] === 1;
        });

        if (sizeof($migrationHasRun)) continue;

        $migration = file_get_contents('./database/migrations/' . $file);

        $database->execute($migration);
        $database->insert('INSERT INTO migrations(migration, run) VALUES(?, ?)', [$migrationName, 1]);

        echo "Migration $migrationName ran successfully \n";
    }
} catch (\PDOException $e) {
    echo $e;
}