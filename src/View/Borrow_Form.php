<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Repository\BorrowRepository;
use App\Config\DatabaseConfig;
use App\Exception\ValidationException;
use app\Exceptions\DatabaseException;

$database = DatabaseConfig::getInstance();
$borrowrepo = new BorrowRepository();

$message = '';
$messageType = '';

if(isset($_POST['borrowBook']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $borrowData = [
            'student_id' => (int)$_POST['student_id'],
            'book_id' => (int)$_POST['book_id'],
            'borrow_days' => (int)$_POST['borrow_days'],
        ];

        $result = $borrowrepo->borrowBook($borrowData['student_id'], $borrowData['book_id'], $borrowData['borrow_days']);


        if(isset($result)){
            $_SESSION['message'] = 'Borrow Success';
            $_SESSION['messageType'] = 'success';
        }else{
            $_SESSION['message'] = 'Failed asdasto borrow';
            $_SESSION['messageType'] = 'error';
        }
    }catch(DatabaseException $e){
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['messageType'] = 'error';
    }

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
    <title>Borrow Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<style>
    .headers{
        text-align: center;
        margin-top: 20px;
    }
    .message{
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
    }
    .message.success{
        background-color: #d4edda;
        color: #155724;
    }
    .message.error{
        background-color: #f8d7da;
        color: #721c24;
    }
    .row{width: 100%;margin:0;}
    </style>
<body>
    <button class="btn btn-secondary mt-3 ms-3"><a href="../../Public/index.php" class="text-decoration-none text-white">Home</a></button>
    <div class="container w-25 mt-5 border p-4 rounded">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="row">
            <h3 class="headers p-0">Borrow Book</h3>
            <?php if($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <label for="student_id">Student ID: </label>
                <input class="form-control" type="number" placeholder="studentID" name="student_id" id="student_id">
            </div>

            <div class="row">
                <label for="book_id">Book ID: </label>
                <input class="form-control" type="number" placeholder="BookID" name="book_id" id="book_id">
            </div>

            <div class="row">
                <label for="borrow_days">Borrow Days: </label>
                <input class="form-control" type="number" placeholder="Borrow Days" name="borrow_days" id="borrow_days" min='1' value=1>
            </div>

            <div class="row">
                <button class="btn btn-primary mt-3 " type="submit" name="borrowBook">Borrow Book</button>
            </div>

        </form>
    </div>
</body>
</html>
