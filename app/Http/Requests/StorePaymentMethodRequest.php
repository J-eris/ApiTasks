<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'method_name' => 'required|string|max:255',
            'details' => 'nullable|string|max:255',
            'proof_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'optional_text' => 'nullable|string',
        ];
    }
}
