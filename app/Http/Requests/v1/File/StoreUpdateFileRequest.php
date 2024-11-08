<?php

namespace App\Http\Requests\v1\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreUpdateFileRequest extends FormRequest
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
                'directory_id' => ['required', 'numeric', 'exists:directories,id'],
                'file' => 'required|file|max:25000',
            ];
        }

        return [
            'directory_id' => ['nullable', 'numeric', 'exists:directories,id'],
            'file' => 'required|file|max:25000',
        ];
    }
}
