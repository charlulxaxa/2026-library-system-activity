<?php
declare(strict_types=1);


namespace App\Library;

require_once __DIR__ . '../vendor/autoload.php';


use App\Repository\DatabaseConnection;
use DateTime;
use DateInterval;
use App\Library\Book;


class BookRepositories{

    private DatabaseConnection $connection;
    private \PDO $pdo;

    public function __construct(){
        $this->connection = DatabaseConnection::getInstance();
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

    public function getBook(int $book_id): Book {
        $book = new Book();
        $sql = "SELECT * FROM Books Where book_id = :book_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':book_id' => $book_id]);
        $result =  $statement->fetch();
        if($result){
            $book->setTitle($result['title']);
            $book->setAuthor($result['author']);
            $book->setYear($result['year']);
            $book->setGenre($result['genre']);
        }
        return $book;
    }
    
    public function listBook(): array {
        $sql = 'SELECT * FROM Books';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function searchBooks(string $keyword): array {
        $sql = "SELECT * FROM books WHERE title LIKE :keyword  OR author LIKE :keyword  ";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':keyword' => '%' . $keyword . '%']);
        $result = $statement->fetchAll();
        $books = [];
        foreach ($result as $row) {
            $book = new Book();
            $book->setTitle($row['title']);
            $book->setAuthor($row['author']);
            $book->setYear($row['year']);
            $book->setGenre($row['genre']);

            $books[] = $book;
         }
        return $books;
    }
}
