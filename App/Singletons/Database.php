<?php
namespace App\Singletons;

use App\Patterns\Singleton;

class Database extends Singleton {

    protected static $instance;
    protected static $connection;

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        static::$connection = $this->initializeConnection();
    }

    /**
     * Returns the database instance (Singleton specific class)
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
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
        } catch(\Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * ORM like functions wrapping the PHP PDO 
     */
    public static function select($query, $params = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);

        $initialStmt->execute($params);

        return $initialStmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function selectAll($query, $params = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);

        $initialStmt->execute($params);

        return $initialStmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function insert($query, $params = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);

        return $initialStmt->execute($params);
    }

    public static function create($query)
    {
        return static::getInstance()::$connection->exec($query);
    }

    public static function update($query, $params = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);

        return $initialStmt->execute($params);

    }

    public static function execute($query) 
    {
        return static::getInstance()::$connection->exec($query);
    } 

    public static function beginTransaction()
    {
        static::getInstance()::$connection->beginTransaction();
    }

    public static function commit()
    {
        static::getInstance()::$connection->commit();
    }

    public static function rollback()
    {
        static::getInstance()::$connection->rollback();
    }
}