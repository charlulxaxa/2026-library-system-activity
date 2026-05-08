<?php
declare(strict_types=1);


namespace App\Service;

use App\Config\LibraryConfig;
use App\Config\DatabaseConfig;


class LibraryReport{

    private DatabaseConfig $connection;
    private \PDO $pdo;

    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

    public function generateReport(): ?array {
        try{
            $this->pdo->beginTransaction();
            $report = [];
            
            $statement = $this->pdo->prepare("SELECT COUNT(*) as c FROM books");
            $report['total_books'] = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $statement2 = $this->pdo->prepare("SELECT COUNT(*) as c FROM borrow_records WHERE status = :status");
            $report['total_borrowed'] = $statement2->execute([':status' => LibraryConfig::STATUS_BORROWED]);

            $statement3 = $this->pdo->prepare("SELECT COUNT(*) as c FROM borrow_records WHERE status = :status");
            $report['total_returned'] = $statement3->execute([':status' => LibraryConfig::STATUS_RETURNED]);
            
            $statement4 = $this->pdo->prepare("SELECT SUM(fine_amount) as s FROM borrow_records WHERE fine_amount > 0");
            $report['total_fines'] = $statement4->execute([]);
            
            $this->pdo->commit();

            return $report;

        }catch (\PDOException $e){
            $this->pdo->rollBack();
            error_log("Query failed: " . $e->getMessage());
            
            return null;
        }
    }
}

