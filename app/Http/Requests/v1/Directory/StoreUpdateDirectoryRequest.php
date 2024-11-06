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
                'name' => [
                    'required', 'string', 'min:3', 'max:255',
                    Rule::unique('directories', 'name')
                        ->where('group_id', auth()->user()->group_id)
                        ->when($this->input('parent_id'), function ($query) {
                            return $query->where('parent_id', $this->input('parent_id'));
                        })->when(!$this->input('parent_id'), function ($query) {
                            return $query->whereNull('parent_id');
                        })
                ],
                'parent_id' => ['nullable', 'numeric', 'exists:directories,id']
            ];
        }

        return [
            'name' => [
                'nullable', 'string', 'min:3', 'max:255',
                Rule::unique('directories', 'name')
                    ->ignore($this->route('directoryId'))
                    ->where('group_id', auth()->user()->group_id)
                    ->when($this->input('parent_id'), function ($query) {
                        return $query->where('parent_id', $this->input('parent_id'));
                    })->when(!$this->input('parent_id'), function ($query) {
                        return $query->whereNull('parent_id');
                    })
            ],
            'parent_id' => ['nullable', 'numeric', 'exists:directories,id'],
        ];
    }
}
