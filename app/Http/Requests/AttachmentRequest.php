<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
                    'auction_id' => 'required|exists:auctions,id',
                    'file_path' => 'required|string|max:255',
                    'file_type' => 'required|in:image,video,link',
                ];
                break;

            case 'PUT':
            case 'PATCH':
                $rules = [
                    'auction_id' => 'sometimes|required|exists:auctions,id',
                    'file_path' => 'sometimes|required|string|max:255',
                    'file_type' => 'nullable|in:image,video,link',
                ];
                break;
        }

        return $rules;
    }
}
