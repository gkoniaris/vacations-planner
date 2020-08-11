<?php
namespace App\Core\Singletons;

use App\Core\Patterns\Singleton;

class Database extends Singleton
{
    protected $connection;

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        $this->connection = $this->initializeConnection();
    }

    /**
     * Creates a new database connection
     */
    private function initializeConnection()
    {
        global $settings;
        
        try {
            $dsn = "mysql:host=" . $settings['DATABASE']['DB_HOST'] . ";dbname=" . $settings['DATABASE']['DB_NAME'];
            
            $connection = new \PDO($dsn, $settings['DATABASE']['DB_USERNAME'], $settings['DATABASE']['DB_PASSWORD'], [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_DIRECT_QUERY => false,
                \PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION
            ]);

            return $connection;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * ORM like functions wrapping the PHP PDO
     */
    public function select($query, $params = [], $model = null)
    {
        $initialStmt = $this->connection->prepare($query);

        $initialStmt->execute($params);

        if ($model) {
            $initialStmt->setFetchMode(\PDO::FETCH_CLASS, $model);

            return $initialStmt->fetch();
        }

        return $initialStmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function selectAll($query, $params = [], $model = null)
    {
        $initialStmt = $this->connection->prepare($query);

        $initialStmt->execute($params);

        if ($model) {
            return $initialStmt->fetchAll(\PDO::FETCH_CLASS, $model);
        }

        return $initialStmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert($query, $params = [])
    {
        $dbh = $this->connection;
        $initialStmt = $this->connection->prepare($query);
        
        $inTransaction = $dbh->inTransaction();
        if (!$inTransaction) {
            $dbh->beginTransaction();
        }

        $initialStmt->execute($params);
        $lastInsertedId = $dbh->lastInsertId();

        if (!$inTransaction) {
            $dbh->commit();
        }

        return $lastInsertedId;
    }

    public function create($query)
    {
        return $this->connection->exec($query);
    }

    public function update($query, $params = [])
    {
        $initialStmt = $this->connection->prepare($query);

        return $initialStmt->execute($params);
    }

    public function execute($query)
    {
        return $this->connection->exec($query);
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }
}
