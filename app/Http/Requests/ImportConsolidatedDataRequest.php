<?php

namespace App\Http\Requests;

use App\Http\Requests\Utility\BaseFormRequest;
use App\Rules\NonEmptyExcelFile;

class ImportConsolidatedDataRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'mimes:xls,xlsx',
                new NonEmptyExcelFile()
            ]
        ];
    }
}
