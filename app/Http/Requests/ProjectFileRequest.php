<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'url' => ['required'],
            'thumbnail' => ['required'],
            'type' => ['required'],
            'size' => ['nullable'],
            'preview' => ['nullable'],
            'meta' => ['nullable'],
        ];
    }
}
