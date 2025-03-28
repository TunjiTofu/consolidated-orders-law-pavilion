<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Maatwebsite\Excel\HeadingRowImport;
use Throwable;

class NonEmptyExcelFile implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Read the headers from the Excel file
            $headings = (new HeadingRowImport())->toArray($value);

            // Ensure there's at least one row of data beyond headers
            if (empty($headings) || empty(array_filter($headings[0]))) {
                $fail("The uploaded file is empty or invalid.");
            }
        } catch (Throwable $e) {
            $fail("The uploaded file could not be processed.");
        }

    }
}
