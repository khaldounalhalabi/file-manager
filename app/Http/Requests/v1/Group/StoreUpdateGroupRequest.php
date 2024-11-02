<?php

namespace App\Http\Requests\v1\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreUpdateGroupRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        if (request()->method() == 'POST') {
            return [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'owner_id' => ['required', 'numeric', 'exists:users,id'],
                'users' => ['array', 'nullable'],
                'users.*' => ['numeric', 'exists:users,id'],
            ];
        }

        return [
            'name' => ['nullable', 'string', 'min:3', 'max:255'],
            'owner_id' => ['nullable', 'numeric', 'exists:users,id'],
            'users' => ['array', 'nullable'],
            'users.*' => ['numeric', 'exists:users,id'],
        ];
    }
}
