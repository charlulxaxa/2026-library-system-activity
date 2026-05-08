<?php
declare(strict_types = 1);


namespace App\Exceptions;


use Override;
use RuntimeException;
use Throwable;

/**
 * Exception thrown when a database operation fails.
 *
 * This exception is used to wrap PDO-related errors and provide
 * a consistent error-handling mechanism across the application.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class DatabaseException extends RuntimeException{
    
    /**
     * DatabaseException constructor
     *
     * @param string $message Error message describing the database failure
     * @param int $code Error code associated with the exception
     * @param Throwable|null $previous Previous exception for chaining
     *
     * @return void
     */
    #[Override]
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
