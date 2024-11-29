<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationUserRequest extends FormRequest
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
            'notification_id' => 'required|exists:notifications,id',
            'user_id' => 'required|exists:users,id',
            'is_read' => 'required|boolean',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'notification_id' => 'sometimes|exists:notifications,id',
                'user_id' => 'sometimes|exists:users,id',
                'is_read' => 'sometimes|boolean',
            ];
        }

        return $rules;
    }
}
