<?php
declare(strict_types = 1);


namespace App\Service;


class Sanitizer {
    
    public static function sanitizeString(string $input): string {
        if ($input === null) {return '';}
        
        $sanitized = trim($input);
        $sanitized = strip_tags($sanitized);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        return $sanitized;
    }
    
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
