<?php
declare(strict_types = 1);


namespace App\Service;


/**
 * Provides input sanitization utilities for user-provided data.
 *
 * This service ensures that strings and arrays are cleaned from
 * malicious input such as HTML tags and unsafe characters to
 * prevent XSS and injection attacks.
 *
 * @author Charlo Marco
 * @since 2026-05-08
 */
class Sanitizer {

    /**
     * Sanitizes a string input by trimming, removing tags,
     * and encoding special characters.
     *
     * @param string $input The raw user input string
     *
     * @return string The sanitized string
     */
    public static function sanitizeString(string $input): string {
        if ($input === null) {return '';}
        
        $sanitized = trim($input);
        $sanitized = strip_tags($sanitized);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        return $sanitized;
    }
    
     /**
     * Sanitizes all string values inside an array.
     *
     * Iterates through the array and applies string sanitization
     * to all string elements while leaving other data types unchanged.
     *
     * @param array $data The input array containing raw values
     *
     * @return array The sanitized array
     */
    public static function sanitizeArray(array $data): array {
        $sanitized = [];
        
        foreach ($data as $field => $value) {
            if(is_string($value)) {
                $sanitized[$field] = self::sanitizeString($value);
            } else {
                $sanitized[$field] = $value;
            }
        }
        
        return $sanitized;
    }
}
