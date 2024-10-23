<?php

namespace App\Http\Requests\v1\User;

use App\Enums\RolesPermissionEnum;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        //TODO::handle profile after creating media class
        if ($this->method() == "POST") {
            return [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|string|' . Rule::in(RolesPermissionEnum::ALLROLES),
                'group_name' => 'required_if:role,' . RolesPermissionEnum::CUSTOMER['role'] . '|string|max:255',
                'profile' => 'nullable|image|max:255',
            ];
        }
        $user = UserRepository::make()->find($this->route('user'));
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user?->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile' => 'nullable|image|max:255',
        ];
    }
}
