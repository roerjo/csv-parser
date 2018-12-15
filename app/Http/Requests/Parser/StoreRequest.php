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
                        $fail($attribute.' must be a csv file');
                    }
                },
            ],
        ];
    }
}
