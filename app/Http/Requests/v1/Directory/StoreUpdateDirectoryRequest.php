<?php

namespace App\Http\Requests\v1\Directory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreUpdateDirectoryRequest extends FormRequest
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
                'parent_id' => ['nullable', 'numeric', 'exists:directories,id'],
                'group_id' => ['required', 'numeric', 'exists:groups,id'],
            ];
        }

        return [
            'name' => ['nullable', 'string', 'min:3', 'max:255'],
            'owner_id' => ['nullable', 'numeric', 'exists:owners,id'],
            'parent_id' => ['nullable', 'numeric', 'exists:parents,id'],
            'group_id' => ['nullable', 'numeric', 'exists:groups,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (auth()->user()?->isCustomer()) {
            $this->merge([
                'group_id' => auth()->user()?->group_id,
            ]);

            if ($this->method() == "POST") {
                $this->merge([
                    'owner_id' => auth()->user()?->id,
                ]);
            }
        }
    }
}
