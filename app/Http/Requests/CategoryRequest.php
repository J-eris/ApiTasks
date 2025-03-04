<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
            ];
        }

        return $rules;
    }
}
