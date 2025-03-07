<?php

namespace App\Http\Requests\v1\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        $guard = request()->acceptsHtml() ? 'web' : 'api';

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . auth($guard)->user()?->id,
            'password' => 'nullable|min:8|confirmed',
            'fcm_token' => 'nullable|string',
            'profile' => 'nullable|image|max:255',
        ];
    }
}
