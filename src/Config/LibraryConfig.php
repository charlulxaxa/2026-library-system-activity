<?php
declare(strict_types=1);


namespace App\Config;

/**
 * LibraryConfig
 *
 * Contains system-wide constants used in the Library Management System.
 * This class centralizes configuration values to ensure consistency
 * and avoid magic numbers/strings across the application.
 * @author Charlo Marco
 * @since 2026-05-08
 */

class LibraryConfig{

    public const STATUS_RETURNED = 'returned';
    public const STATUS_BORROWED = 'borrowed';
    public const SECONDS_PER_DAY = 60 * 60 * 24;
    public const DAILY_FINE_RATE = 5.00;
    public const MAX_BORROW_LIMIT = 3;
    public const DEFAULT_BORROW_DAYS = 14;

}
