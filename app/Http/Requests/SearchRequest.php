<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
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
            'type' => [
                'required',
                Rule::in(['beer', 'brewery']),
            ],
            'q' => [
                'required',
                'regex:/^([A-Za-z0-9]|\s|-)+$/'
            ],
//            'page' => [
//                'integer',
//                'min:1'
//            ]
        ];
    }
}
