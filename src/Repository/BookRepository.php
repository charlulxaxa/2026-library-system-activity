<?php
declare(strict_types=1);


namespace App\Repository;


use App\Config\DatabaseConfig;
use App\Entity\Book;
use App\Exceptions\DatabaseException;

/**
 * Handles all database operations related to Book entities.
 *
 * This repository encapsulates SQL queries for books, ensuring
 * separation of concerns and secure database access using PDO
 * prepared statements.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class BookRepository{

    private DatabaseConfig $connection;
    private \PDO $pdo;

    /**
     * BookRepository constructor
     *
     * Initializes the repository with a database connection.
     *
     */
    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

    /**
     * Inserts a new book into the database.
     *
     * @param Book $book The book entity to be saved
     *
     * @return int|null The generated book ID or null on failure
     *
     * @throws DatabaseException If database insertion fails
     */
    public function addBook(Book $book): ?int {
        try{
            $sql = "INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)";
            $statement= $this->pdo->prepare($sql);
            $statement->execute([
                    $book->getTitle(),
                    $book->getAuthor(),
                    $book->getYear(),
                    $book->getGenre()
                ]
            );
            return (int) $this->pdo->lastInsertId();

        }catch (DatabaseException $e){
            error_log("Query failed: " . $e->getMessage());
            throw new DatabaseException("Failed to add book", 0, $e);
        }
    }

    /**
     * Retrieves a single book by ID.
     *
     * @param int $book_id The ID of the book
     *
     * @return Book|null The book object or null if not found
     */
    public function getBook(int $book_id): ?Book {
        $sql = "SELECT * FROM Books Where book_id = :book_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':book_id' => $book_id]);
        $result =  $statement->fetch(\PDO::FETCH_ASSOC);
        if($result){
            $book = new Book(
                $result['title'],
                $result['author'],
                $result['year'],
                $result['genre']
            );
        }
        return $book ?? null;
    }
    
    /**
     * Retrieves all books from the database.
     *
     * @return array List of books (raw database format)
     */
    public function listBook(): ?array {
        $sql = 'SELECT * FROM Books';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Searches books by title or author keyword.
     *
     * @param string $keyword Search keyword
     *
     * @return array List of matching Book objects
     */
    public function searchBooks(string $keyword): ?array {
        $sql = "SELECT * FROM books WHERE title LIKE :keyword  OR author LIKE :keyword  ";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':keyword' => '%' . $keyword . '%']);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $books = [];
        foreach ($result as $row) {
            $book = new Book(
                $row['title'],
                $row['author'],
                $row['year'],
                $row['genre']
            );

            $books[] = $book;
         }
        return $books;
    }
}
