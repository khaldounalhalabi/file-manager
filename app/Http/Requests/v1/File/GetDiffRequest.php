<?php

namespace App\Http\Requests\v1\File;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetDiffRequest extends FormRequest
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
            'first_file_id' => [Rule::exists('file_versions', 'id'), 'numeric'],
            'second_file_id' => [Rule::exists('file_versions', 'id'), 'numeric'],
        ];
    }
}
