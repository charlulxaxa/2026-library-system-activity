<?php
declare(strict_types=1);


namespace App\Service;


use App\Config\LibraryConfig;
use App\Config\DatabaseConfig;
use App\Exceptions\DatabaseException;


/**
 * Generates summary reports for the library system.
 *
 * This service aggregates data such as total books,
 * borrowed books, returned books, and total fines.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class LibraryReport{

    private DatabaseConfig $connection;
    private \PDO $pdo;

    /**
     * LibraryReport constructor
     *
     * Initializes the report service with a database connection.
     *
     */
    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

    /**
     * Generates a summary report for the library system.
     *
     * @return array|null The generated report data or null if an error occurs
     */
    public function generateReport(): ?array {
        try{
            $this->pdo->beginTransaction();
            $report = [];
            
            $statement = $this->pdo->prepare("SELECT COUNT(*) as c FROM books");
            $statement->execute();
            $report['total_books'] = $statement->fetchColumn();

            $statement2 = $this->pdo->prepare("SELECT COUNT(*) as c FROM borrow_records WHERE status = :status");
            $statement2->execute([':status' => LibraryConfig::STATUS_BORROWED]);
            $report['total_borrowed'] = $statement2->fetchAll(\PDO::FETCH_ASSOC);

            $statement3 = $this->pdo->prepare("SELECT COUNT(*) as c FROM borrow_records WHERE status = :status");
            $statement3->execute([':status' => LibraryConfig::STATUS_RETURNED]);
            $report['total_returned'] = $statement3->fetchAll(\PDO::FETCH_ASSOC);

            $statement4 = $this->pdo->prepare("SELECT SUM(fine_amount) as s FROM borrow_records WHERE fine_amount > 0");
            $statement4->execute();
            $report['total_fines'] = $statement4->fetchAll(\PDO::FETCH_ASSOC);

            $this->pdo->commit();

            return $report;

        }catch (\PDOException $e){
            $this->pdo->rollBack();
            throw new DatabaseException("Query failed: " . $e->getMessage());
        }
    }
}

