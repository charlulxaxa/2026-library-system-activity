<?php
declare(strict_types=1);


namespace App\Repository;


use App\Config\LibraryConfig;
use DateTime;
use DateInterval;
use App\Config\DatabaseConfig;
use App\Service\LibraryService;
use App\Exceptions\DatabaseException;

/**
 * Handles borrowing and returning operations for library books.
 *
 * This repository manages all borrow-related database transactions,
 * including validation of students, books, borrowing rules, and fines.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class BorrowRepository{
    private DatabaseConfig $connection;
    private \PDO $pdo;

    /**
     * BorrowRepository constructor
     *
     * Initializes the repository with a database connection.
     *
     */
    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

    /**
     * Borrows a book for a student.
     *
     * Validates student existence, book existence, and availability
     * before creating a borrow record in the database.
     *
     * @param int $studentId The ID of the student borrowing the book
     * @param int $bookId The ID of the book to borrow
     * @param int $days Number of days the book will be borrowed
     *
     * @return bool True if borrowing is successful
     *
     * @throws DatabaseException If student or book does not exist,
     *         or if the book is already borrowed
     */
    public function borrowBook(int $studentId, int $bookId, int $days): bool {
        try{
            $this->pdo->beginTransaction();

            // Validate student existence
            $sql = "SELECT student_id FROM students WHERE student_id = :student_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':student_id' => $studentId]);

            if (!$stmt->fetch()) {
                throw new DatabaseException("Student does not exist");
            }

            // Validate book existence and availability 
            $check = $this->pdo->prepare("SELECT book_id FROM books WHERE book_id = :book_id");
            $check->execute([':book_id' => $bookId]);

            if (!$check->fetch()) {
                throw new DatabaseException("Book does not exist");
            }

            // Check if book is already borrowed
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

            // Insert borrow record
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

    /**
     * Returns a borrowed book and calculates any applicable fine.
     *
     * @param int $recordId The ID of the borrow record
     *
     * @return float|null The calculated fine amount or null if not applicable
     *
     * @throws DatabaseException If the borrow record does not exist or is already returned
     */
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
