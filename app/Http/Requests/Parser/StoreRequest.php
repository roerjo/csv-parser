<?php

namespace App\Http\Requests\Parser;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv_data' => [
                'required',
                'file',
                // Testing with UploadedFile ruins the use of the `mimes` and
                // the `mimestype` rule. Using a one-off closure rule instead.
                function ($attribute, $value, $fail) {
                    if ($value->getClientMimeType() !== 'text/csv') {
                        $fail('Only CSV files allowed');
                    }
                },
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'csv_data.required' => 'A CSV file is required',
            'csv_data.file' => 'The file did not upload successfully',
        ];
    }
}
