<?php

namespace App\Http\Requests\v1\File;

use App\Repositories\FileRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PushFileUpdateRequest extends FormRequest
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
        $file = FileRepository::make()->find($this->input('file_id'), ['lastVersion']);
        return [
            'file_id' => 'required|numeric|exists:files,id',
            'file' => ['required', 'file', 'max:25000', function ($attribute, $value, $fail) use ($file) {
                if ($file->getFileName() != $this->file('file')?->getClientOriginalName()) {
                    $fail("You should upload a file with the same name of the previous one.");
                }
            }],
        ];
    }
}
