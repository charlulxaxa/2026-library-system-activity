<?php
declare(strict_types=1);


namespace App\Config;


class LibraryConfig{

    public const STATUS_RETURNED = 'returned';
    public const STATUS_BORROWED = 'borrowed';
    public const SECONDS_PER_DAY = 60 * 60 * 24;
    public const FINE_RATE = 5;
    public const MAX_BORROW_LIMIT = 3;
    public const DEFAULT_BORROW_DAYS = 14;

}
