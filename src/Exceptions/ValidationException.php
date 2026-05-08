<?php
declare(strict_types=1);


namespace App\Exceptions;


use InvalidArgumentException;
use Throwable;
use Override;

/**
 * Exception thrown when input validation fails.
 *
 * This exception is used to enforce data integrity by handling
 * invalid or missing user input before database operations occur.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class ValidationException extends InvalidArgumentException{

    /**
     * ValidationException constructor
     *
     * @param string $message Error message describing the validation failure
     * @param int $code Error code (optional)
     * @param Throwable|null $previous Previous exception for chaining
     *
     * @return void
     */
    #[Override]
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
