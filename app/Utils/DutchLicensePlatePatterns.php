<?php

namespace App\Utils;

class DutchLicensePlatePatterns
{
    public static function validationPatterns(): string
    {
        return '/^('
            .'[A-Z]\d{3}[A-Z]{2}|'      // A001AA
            .'[A-Z]{2}\d{3}[A-Z]|'      // AA001A
            .'\d{2}[A-Z]{3}\d|'         // 00AAA1
            .'\d[A-Z]{3}\d{2}|'         // 0AAA01
            .'[A-Z]{3}\d{2}[A-Z]|'      // AAA01A
            .'[A-Z]\d{2}[A-Z]{3}|'      // A01AAA
            .'\d[A-Z]{2}\d{3}'          // 0AA001
            .')$/';
    }

    public static function formattingPatterns(): array
    {
        return [
            '/^([A-Z])(\d{3})([A-Z]{2})$/' => '$1-$2-$3',   // A-001-AA
            '/^([A-Z]{2})(\d{3})([A-Z])$/' => '$1-$2-$3',   // AA-001-A
            '/^(\d{2})([A-Z]{3})(\d)$/' => '$1-$2-$3',    // 00-AAA-1
            '/^(\d)([A-Z]{3})(\d{2})$/' => '$1-$2-$3',    // 0-AAA-01
            '/^([A-Z]{3})(\d{2})([A-Z])$/' => '$1-$2-$3',   // AAA-01-A
            '/^([A-Z])(\d{2})([A-Z]{3})$/' => '$1-$2-$3',   // A-01-AAA
            '/^(\d)([A-Z]{2})(\d{3})$/' => '$1-$2-$3',    // 0-AA-001
        ];
    }
}
