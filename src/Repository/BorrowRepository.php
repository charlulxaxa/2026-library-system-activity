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
        try{
            $this->pdo->beginTransaction();

            $sql = "SELECT student_id FROM students WHERE student_id = :student_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':student_id' => $studentId]);

            if (!$stmt->fetch()) {
                throw new DatabaseException("Student does not exist");
            }
            
            $check = $this->pdo->prepare("SELECT book_id FROM books WHERE book_id = :book_id");
            $check->execute([':book_id' => $bookId]);

            if (!$check->fetch()) {
                throw new DatabaseException("Book does not exist");
            }

            $sql = "SELECT * FROM borrow_records
            WHERE book_id = :book_id
            AND status = :status";

            $statement = $this->pdo->prepare($sql);
                $statement->execute([
                ':book_id' => $bookId,
                ':status' => LibraryConfig::STATUS_BORROWED
            ]);

            $existing = $statement->fetch(\PDO::FETCH_ASSOC);

            if ($existing) {
                throw new DatabaseException("Book is already borrowed");
            }

            $due = date('Y-m-d', strtotime('+' . $days . ' days'));

            $sql = "INSERT INTO borrow_records
                (student_id, book_id, borrow_date, due_date, status)
                VALUES (:student_id, :book_id, :borrow_date, :due_date, :status)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute([
                ':student_id' => $studentId,
                ':book_id' => $bookId,
                ':borrow_date' => date('Y-m-d'),
                ':due_date' => $due,
                ':status' => LibraryConfig::STATUS_BORROWED
            ]);

            $this->pdo->commit();

            return true;
        }
        catch (DatabaseException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function returnBook(int $recordId): ?float {
        try{
            $this->pdo->beginTransaction();

            $sql = "SELECT * FROM borrow_records WHERE record_id= :record_id";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([':record_id' => $recordId]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new DatabaseException("Failed to return book", 0);
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
            throw new DatabaseException("Failed to return book", 0, $e);
        }
    }
}
