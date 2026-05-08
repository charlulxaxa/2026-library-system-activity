<?php
declare(strict_types=1);
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Repository\BookRepository;
use App\Config\DatabaseConfig;
use App\Exceptions\DatabaseException;


$database = DatabaseConfig::getInstance();
$bookRepo = new BookRepository();

   
$bookList = [];

try {
    $bookList = $bookRepo->listBook();
} catch (DatabaseException $e) {
    error_log("Database error: " . $e->getMessage());
    throw new DatabaseException("An error occurred while fetching the book list.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <button class="btn btn-secondary mt-3 ms-3"><a href="../../Public/index.php" class="text-decoration-none text-white">Home</a></button>
    <h3 class="BookList-header">Book List</h3>
    
    <table class="table bookList">
        <thead>
            <tr>
                <th>BookId</th>
                <th>Book Title</th>
                <th>Book Author</th>
                <th>Book Year</th>
                <th>Genre</th>
            </tr>
        </thead>
        <tbody>
        <?php if(empty($bookList)): ?>
            <p>No book List Found</p>
        <?php else: ?>
            <?php foreach($bookList as $book): ?>
                <tr>
                    <td><?php echo $book['book_id'] ?></td>
                    <td><?php echo $book['title'] ?></td>
                    <td><?php echo $book['author'] ?></td>
                    <td><?php echo $book['year'] ?></td>
                    <td><?php echo $book['genre'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
