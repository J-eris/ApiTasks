<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [];

        switch ($this->method()) {
            case 'POST':
                $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                    'phone' => 'nullable|string|max:20',
                    'roles.*' => 'exists:roles,name',
                    'balance' => 'nullable|numeric',
                    'address' => 'nullable|string',
                    'birthday' => 'nullable|date',
                    'account_status' => 'in:active,inactive',
                ];
                break;

            case 'PUT':
            case 'PATCH':
                $userId = $this->route('user');
                $rules = [
                    'name' => 'sometimes|required|string|max:255',
                    'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
                    'phone' => 'nullable|string|max:20',
                    'roles.*' => 'exists:roles,name',
                    'balance' => 'nullable|numeric',
                    'address' => 'nullable|string',
                    'birthday' => 'nullable|date',
                    'account_status' => 'in:active,inactive',
                ];
                break;

            case 'PATCH':
                if ($this->route()->getName() === 'users.updateStatus') {
                    $rules = [
                        'account_status' => 'required|in:active,inactive',
                    ];
                }
                break;
        }

        return $rules;
    }
}
