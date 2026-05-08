<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../vendor/autoload.php';


use App\Config\DatabaseConfig;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Exception\ValidationException;


$db = DatabaseConfig::getInstance();
$bookrepo = new BookRepository();

const LOCATION = 'Location: ../src/View/';
$message = '';
$messageType = '';


if (isset($_POST['BookList']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header(LOCATION . 'Book_list.php');
    exit();
}
if (isset($_POST['BorrowForm']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header(LOCATION. 'Borrow_Form.php');
    exit();
}

if(isset($_POST['addBook']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $bookData = [
            'title' => $_POST['book_title'],
            'author' => $_POST['book_author'],
            'genre' => $_POST['book_genre'],
            'year' => (int)$_POST['book_year'],
        ];

        $book = new Book(
        $bookData['title'],
        $bookData['author'],
        $bookData['year'],
        $bookData['genre']
        );

        $result = $bookrepo->addBook($book);


        if(isset($result)){
            $_SESSION['message'] = 'Add book Success';
            $_SESSION['messageType'] = 'success';
        }else{
            $_SESSION['message'] = 'Failed to add a book';
            $_SESSION['messageType'] = 'error';
        }
    }catch(ValidationException){
        $_SESSION['message'] = 'Failed to add a book';
        $_SESSION['messageType'] = 'error';
    }

    $book = null;

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <h3 class="headers">Add Book</h3>
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <label for="book_title">Title: </label>
                <input class="form-control" type="text" placeholder="enter the title" name="book_title" id="book_title">
            </div>

             <div class="row">
                <label for="book_author">Author: </label>
                <input class="form-control" type="text" placeholder="enter the author" name="book_author" id="book_author">
            </div>

             <div class="row">
                <label for="book_genre">Genre: </label>
                <input class="form-control" type="text" placeholder="enter the genre" name="book_genre" id="book_genre">
            </div>

            <div class="row">
                <label for="book_year">Year: </label>
                <input class="form-control" type="int" placeholder="enter the Year" name="book_year" id="book_year">
            </div>

             <div class="row">
                <button class="btn btn-success mt-3" type="submit" name="addBook">Add Book</button>
            </div>
        </form>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <button class="btn btn-primary mt-3" type="submit" name="BookList">View Book List</button>
            <button class="btn btn-primary mt-3" type="submit" name="BorrowForm">Borrow Book</button>
        </form>
</body>

</html>
