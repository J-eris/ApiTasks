<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'auction_id' => 'required|exists:auctions,id',
            'description' => 'required|string',
            'status' => 'in:open,closed',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'user_id' => 'sometimes|required|exists:users,id',
                'auction_id' => 'sometimes|required|exists:auctions,id',
                'description' => 'sometimes|string',
                'status' => 'in:open,closed',
            ];
        }

        return $rules;
    }
}
