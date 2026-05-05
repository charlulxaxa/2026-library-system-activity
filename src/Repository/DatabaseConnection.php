<?php
declare(strict_types=1);


namespace App\Repository;


use App\Config\EnvParser;
use RuntimeException;


class DatabaseConnection{

    private \PDO $conn;
    private array $config;
    private static ?DatabaseConnection $instance = null;

    public function __construct(){
        $this->loadConfig();
        $this->connect();
    }

    private function __clone() {
        //Prevents Cloning
    }
    
    public function __wakeup() {
        throw new RuntimeException("Cannot unserialize singleton");
    }

    private function loadConfig(){
        $this->config = [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: '3306',
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
            'driver' => getenv('DB_DRIVER') ?: 'mysql'
        ];
        
        if (!$this->config['name'] || !$this->config['user']) {
            throw new \RuntimeException("Database name and user are required in .env file");
        }
    }

    private function connect(){
        try {
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $this->config['driver'],
                $this->config['host'],
                $this->config['port'],
                $this->config['name'],
                $this->config['charset']
            );
            
            $this->conn = new \PDO(
                $dsn,
                $this->config['user'],
                $this->config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage(),0 , $e);
        }
    }
    
    public static function getInstance(): DatabaseConnection {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): \PDO {
        return $this->conn;
    }
    
    public function lastInsertId(): string {
        return $this->conn->lastInsertId();
    }
    
    public function beginTransaction(): bool {
        return $this->conn->beginTransaction();
    }
    
    public function commit(): bool {
        return $this->conn->commit();
    }
    
    public function rollBack(): bool {
        return $this->conn->rollBack();
    }
}
