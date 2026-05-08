<?php
declare(strict_types=1);


namespace App\Config;


use App\Config\EnvParser;
use App\Exceptions\DatabaseException;

/**
 * DatabaseConfig
 *
 * Singleton class responsible for managing database connection using PDO.
 * Loads configuration from environment variables and establishes a secure connection.
 */
$env = new EnvParser();
$env->load(__DIR__ . '/../../.env');

/**
 * Class DatabaseConfig
 *
 * Handles database connection, configuration loading, and transaction control.
 * Implements Singleton pattern to ensure only one PDO connection exists.
 * @author Charlo Marco
 * @since 2026-05-08
 */
class DatabaseConfig{

    /**
     * PDO connection instance
     */
    private \PDO $conn;

    /**
     * Database configuration array loaded from environment variables
     *
     * @var array<string, string|null>
     */
    private array $config;

    /**
     * Singleton instance
     */
    private static ?DatabaseConfig $instance = null;

    /**
     * DatabaseConfig constructor
     *
     * Loads configuration and establishes database connection.
     *
     * @throws DatabaseException if required configuration is missing or connection fails
     */
    public function __construct(){
        $this->loadConfig();
        $this->connect();
    }

    /**
     * Prevent cloning of singleton instance
     */
    private function __clone() {
        //Prevents Cloning
    }
    
    public function __wakeup() {
        throw new DatabaseException("Cannot unserialize singleton");
    }

    /**
     * Load database configuration from environment variables
     *
     * Required variables:
     * - DB_HOST
     * - DB_PORT
     * - DB_NAME
     * - DB_USER
     * - DB_PASSWORD
     *
     * Optional:
     * - DB_CHARSET (default: utf8mb4)
     * - DB_DRIVER (default: mysql)
     *
     * @throws DatabaseException if required variables are missing
     */
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
            throw new DatabaseException("Database name and user are required in .env file");
        }
    }

    /**
     * Establish PDO database connection
     *
     * @throws DatabaseException if connection fails
     */
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
            throw new DatabaseException("Database connection failed: " . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Get singleton instance of DatabaseConfig
     *
     * @return DatabaseConfig
     */
    public static function getInstance(): DatabaseConfig {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
     /**
     * Get PDO connection instance
     *
     * @return \PDO
     */
    public function getConnection(): \PDO {
        return $this->conn;
    }
    
    /**
     * Get last inserted ID from database
     *
     * @return string
     */
    public function lastInsertId(): string {
        return $this->conn->lastInsertId();
    }
    
    /**
     * Begin database transaction
     *
     * @return bool
     */
    public function beginTransaction(): bool {
        return $this->conn->beginTransaction();
    }
    
    /**
     * Commit current transaction
     *
     * @return bool
     */
    public function commit(): bool {
        return $this->conn->commit();
    }

    /**
     * Rollback current transaction
     *
     * @return bool
     */
    public function rollBack(): bool {
        return $this->conn->rollBack();
    }
}
