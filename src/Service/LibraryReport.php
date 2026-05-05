<?php
declare(strict_types=1);


use App\Repository\DatabaseConnection;


class LibraryReport{

    private DatabaseConnection $connection;
    private \PDO $pdo;

    public function __construct(){
        $this->connection = DatabaseConnection::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

}

