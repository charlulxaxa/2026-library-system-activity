<?php
declare(strict_types=1);


namespace App\Repository;


use App\Config\DatabaseConfig;
use DateTime;
use DateInterval;
use App\Entity\Book;


class BookRepository{

    private DatabaseConfig $connection;
    private \PDO $pdo;

    public function __construct(){
        $this->connection = DatabaseConfig::getInstance();
        $this->pdo = $this->connection->getConnection();
    }

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

        }catch (\PDOException $e){
            error_log("Query failed: " . $e->getMessage());
            return null;
        }
    }

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
    
    public function listBook(): ?array {
        $sql = 'SELECT * FROM Books';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

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
