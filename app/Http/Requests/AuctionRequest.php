<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuctionRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'starting_price' => 'required|numeric',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'reference_price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'created_by' => 'nullable|exists:users,id',
        ];

        if ($this->isMethod('post')) {
            $rules['description'] = 'required|string';
            $rules['min_price'] = 'required|numeric';
            $rules['max_price'] = 'required|numeric';
            $rules['reference_price'] = 'required|numeric';
            $rules['created_by'] = 'required|exists:users,id';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'title' => 'sometimes|required|string|max:255',
                'category_id' => 'sometimes|required|exists:categories,id',
                'starting_price' => 'sometimes|required|numeric',
                'min_price' => 'sometimes|nullable|numeric',
                'max_price' => 'sometimes|nullable|numeric',
                'reference_price' => 'sometimes|nullable|numeric',
                'description' => 'sometimes|nullable|string',
                'created_by' => 'sometimes|nullable|exists:users,id',
            ];
        }

        return $rules;
    }
}
