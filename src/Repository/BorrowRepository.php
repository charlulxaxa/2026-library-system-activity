<?php
declare(strict_types=1);


namespace App\Repository;


use App\Config\LibraryConfig;
use DateTime;
use DateInterval;
use App\Config\DatabaseConfig;
use App\Service\LibraryService;
use App\Exceptions\DatabaseException;


class BorrowRepository{
    private DatabaseConfig $connection;
    private \PDO $pdo;

    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

    public function borrowBook(int $studentId, int $bookId, int $days): bool {
        $due = date('Y-m-d', strtotime('+' . $days . ' days'));

        $sql = "INSERT INTO borrow_records
            (student_id, book_id, borrow_date, due_date, status)
            VALUES (:student_id, :book_id, :borrow_date, :due_date, :status)";

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            ':student_id' => $studentId,
            ':book_id' => $bookId,
            ':borrow_date' => date('Y-m-d'),
            ':due_date' => $due,
            ':status' => LibraryConfig::STATUS_BORROWED
        ]);
    }

    public function returnBook(int $recordId): ?float {
        try{
            $this->pdo->beginTransaction();

            $sql = "SELECT * FROM borrow_records WHERE record_id= :record_id";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':record_id' => $recordId]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                $this->pdo->rollBack();
                return null;
            }
            $fine = LibraryService::calculateOverdueFine($result['due_date'],LibraryConfig::DAILY_FINE_RATE);
            
            $sql2 = "UPDATE borrow_records SET return_date = :return_date , fine_mount = :fine_amount , status = :status WHERE record_id = :record_id  AND status != 'returned'";
            $statement2 = $this->pdo->prepare($sql2);
            $statement2->execute([
                ':return_date' => date('Y-m-d'),
                ':fine_amount' => $fine,
                ':status' => LibraryConfig::STATUS_RETURNED,
                ':record_id' => $recordId
            ]);

            $this->pdo->commit();

            return $fine;
        }
        catch (DatabaseException $e) {
            $this->pdo->rollBack();
            error_log("Update error: " . $e->getMessage());
            throw new DatabaseException("Failed to return book", 0, $e);
        }
    }
}
