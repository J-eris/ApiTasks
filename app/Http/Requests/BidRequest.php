<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
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
            'auction_id' => 'required|exists:auctions,id',
            'user_id' => 'required|exists:users,id',
            'bid_price' => 'required|numeric',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'bid_price' => 'sometimes|required|numeric',
            ];
        }

        return $rules;
    }
}
