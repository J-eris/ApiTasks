<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'sender_id' => 'sometimes|required|exists:users,id',
                'receiver_id' => 'sometimes|required|exists:users,id',
                'message' => 'sometimes|string',
            ];
        }

        return $rules;
    }
}
