<?php

namespace App\Http\Requests\v1\File;

use App\Enums\FileStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditMultipleFilesRequest extends FormRequest
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
            'files_ids' => 'array|required',
            'files_ids.*' => [Rule::exists('files', 'id')->where('status', FileStatusEnum::UNLOCKED->value)],
        ];
    }
}
