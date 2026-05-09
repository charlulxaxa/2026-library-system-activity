<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '../../../vendor/autoload.php';


use App\Config\DatabaseConfig;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use App\Service\LibraryService;
use App\Exceptions\DatabaseException;
use App\Service\LibraryReport;


/**
 * Displays library system statistics including:
 * - Total books
 * - Total borrowed books
 * - Total returned books
 * - Total fines collected
 *
 * This view aggregates data from LibraryReport service
 * and presents it in a user-friendly dashboard format.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
$db = DatabaseConfig::getInstance();
$bookrepo = new BookRepository();
$borrowrepo = new BorrowRepository();

$message = '';
$messageType = '';

$books = $bookrepo->listBook();

try {
    $report = new LibraryReport();
    $reportData = $report->generateReport();
} catch (DatabaseException $e) {
    $message = $e->getMessage();
    $messageType = 'error';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<style>
    .message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
    }
    li {
        list-style-type: none;
        font-size: 18px;
        margin-bottom: 10px;
    }
</style>
<body>
    <button class="btn btn-secondary mt-3 ms-3"><a href="../../Public/index.php" class="text-decoration-none text-white">Home</a></button>
    <div class="container w-50 mt-5 p-4 border rounded text-center">
        <h1>Library Report</h1>
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($reportData)): ?>
            <p>No report data found</p>
        <?php else: ?>
            <ul>
                <li>Total Books: <?php echo $reportData['total_books']; ?></li>
                <li>Total Borrowed: <?php echo $reportData['total_borrowed'][0]['c']; ?></li>
                <li>Total Returned: <?php echo $reportData['total_returned'][0]['c']; ?></li>
                <li>Total Fines: <?php echo $reportData['total_fines'][0]['s'] ?? '0'; ?></li>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>