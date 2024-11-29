<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'user_id' => 'exists:users,id',
            'type' => 'required|in:email,push',
            'message' => 'required|string',
            'is_read' => 'boolean',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'user_id' => 'sometimes|exists:users,id',
                'type' => 'sometimes|in:email,push',
                'message' => 'sometimes|string',
                'is_read' => 'boolean',
            ];
        }

        return $rules;
    }
}
