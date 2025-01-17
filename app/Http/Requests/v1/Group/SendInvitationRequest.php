<?php

namespace App\Http\Requests\v1\Group;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email|min:3|max:255|required_without:user_id',
            'user_id' => 'numeric|required_without:email|exists:users,id',
            'group_id' => [
                'required',
                'numeric',
                Rule::exists('groups', 'id')
                    ->where('owner_id', auth()->user()->id)
            ],
        ];
    }
}
