<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'description' => ['required','string'],
            'url' => ['required','string'],
            'thumbnail' => ['required','longtext'],
            'preview' => ['required','integer'],
            'size' => ['required','integer'],
            'duration' => ['required','string'],
            'meta' => ['required','json'],
            'processed_at' => ['required','datetime']

        ];
    }
}