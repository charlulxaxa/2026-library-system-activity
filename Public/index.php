<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../vendor/autoload.php';


use App\Config\DatabaseConfig;

$db = DatabaseConfig::getInstance();

if(isset($_POST['BookList']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('Location: ' . '../src/View/Book_list.php');
    exit();
}
if(isset($_POST['BorrowForm']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('Location: ' . '../src/View/Borrow_Form.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <button type="submit" name="BookList">View Book List</button>
        <button type="submit" name="BorrowForm">Borrow Book</button>
    </form>
</body>
</html>
