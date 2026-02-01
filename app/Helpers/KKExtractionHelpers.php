<?php

if (!function_exists('romanToArabic')) {
    /**
     * Convert Roman numerals to Arabic numbers
     */
    function romanToArabic($roman)
    {
        $romans = [
            'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
            'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10,
            'XI' => 11, 'XII' => 12, 'XIII' => 13, 'XIV' => 14
        ];

        $roman = strtoupper(trim($roman));
        return $romans[$roman] ?? null;
    }
}

if (!function_exists('getField')) {
    /**
     * Get field value case-insensitively
     */
    function getField($record, $fieldName, $default = null)
    {
        // Try uppercase
        if (isset($record[strtoupper($fieldName)])) {
            return $record[strtoupper($fieldName)];
        }
        // Try lowercase
        if (isset($record[strtolower($fieldName)])) {
            return $record[strtolower($fieldName)];
        }
        // Try as-is
        if (isset($record[$fieldName])) {
            return $record[$fieldName];
        }
        return $default;
    }
}

if (!function_exists('normalizeStatusPerkawinan')) {
    /**
     * Normalize status_perkawinan to match database enum
     */
    function normalizeStatusPerkawinan($status)
    {
        if (!$status || $status === '-') {
            return 'BELUM KAWIN';
        }

        $status = strtoupper(trim($status));

        // Map various formats to standard enum values
        if (stripos($status, 'BELUM') !== false || stripos($status, 'TIDAK') !== false) {
            return 'BELUM KAWIN';
        }
        if (stripos($status, 'KAWIN') !== false && stripos($status, 'CERAI') === false) {
            return 'KAWIN';
        }
        if (stripos($status, 'CERAI HIDUP') !== false || stripos($status, 'CERAI') !== false && stripos($status, 'MATI') === false) {
            return 'CERAI HIDUP';
        }
        if (stripos($status, 'CERAI MATI') !== false || stripos($status, 'MATI') !== false) {
            return 'CERAI MATI';
        }

        return 'BELUM KAWIN'; // Default
    }
}

if (!function_exists('normalizeStatusDalamKeluarga')) {
    /**
     * Normalize status_dalam_keluarga to match database enum
     * Maps numeric codes to text values
     */
    function normalizeStatusDalamKeluarga($status)
    {
        if (!$status || $status === '-' || $status === '') {
            return 'LAINNYA';
        }

        // Map numeric codes from KK data
        $mapping = [
            '1' => 'KEPALA KELUARGA',
            '2' => 'SUAMI',
            '3' => 'ISTRI',
            '4' => 'ANAK',
            '5' => 'MENANTU',
            '6' => 'CUCU',
            '7' => 'ORANGTUA',
            '8' => 'MERTUA',
            '9' => 'FAMILI LAIN',
            '10' => 'PEMBANTU',
            '11' => 'LAINNYA',
        ];

        $status = trim($status);

        // If numeric code
        if (isset($mapping[$status])) {
            return $mapping[$status];
        }

        // If already text, try to match
        $status = strtoupper($status);
        $validValues = ['KEPALA KELUARGA', 'SUAMI', 'ISTRI', 'ANAK', 'MENANTU', 'CUCU', 'ORANGTUA', 'MERTUA', 'FAMILI LAIN', 'PEMBANTU', 'LAINNYA'];
        foreach ($validValues as $valid) {
            if (stripos($status, $valid) !== false || stripos($valid, $status) !== false) {
                return $valid;
            }
        }

        return 'LAINNYA'; // Default
    }
}
