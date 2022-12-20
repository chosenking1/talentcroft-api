<?php

namespace App\Http\Requests;

use App\Models\Movie;
use Illuminate\Foundation\Http\FormRequest;

class MovieFileRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'url'=> 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,|required|max:102400',
            'thumbnail' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'size' => 'nullable',
            'preview' => 'nullable',
            'meta' => 'nullable',
        ];
    }
}
