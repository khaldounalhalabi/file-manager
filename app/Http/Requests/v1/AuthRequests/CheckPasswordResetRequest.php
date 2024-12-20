<?php

namespace App\Http\Requests\v1\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class CheckPasswordResetRequest extends FormRequest
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
            'reset_password_code' => 'required|string|exists:users,reset_password_code',
        ];
    }
}
