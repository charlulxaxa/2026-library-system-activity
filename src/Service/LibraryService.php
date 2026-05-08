<?php
declare(strict_types=1);


namespace App\Service;


use DateTime;


/**
 * Provides core business logic for library operations.
 *
 * This service handles calculations such as overdue fines
 * based on due dates and configurable daily rates.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class LibraryService{
    
    /**
     * Calculates overdue fine based on due date and daily rate.
     *
     * Computes the number of days a book is overdue and multiplies it
     * by the configured daily fine rate. Returns 0 if the book is not overdue.
     *
     * @param DateTime $dueDate The due date of the borrowed book
     * @param float $dailyRate The fine rate per day
     *
     * @return float The total calculated fine (0.0 if not overdue)
     */
    public static function calculateOverdueFine(DateTime $dueDate, float $dailyRate): float{
        $today = new DateTime();
        $diff = $today->diff($dueDate);
        $daysOverdue = (int) $diff->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $dailyRate : 0.0;
    }


}
