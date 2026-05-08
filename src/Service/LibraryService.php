<?php
declare(strict_types=1);


namespace App\Service;

use DateTime;
use DateInterval;

class LibraryService{
    
    public static function calculateOverdueFine(DateTime $dueDate, float $dailyRate): float{
        $today = new DateTime();
        $diff = $today->diff($dueDate);
        $daysOverdue = (int) $diff->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $dailyRate : 0.0;
    }

}
