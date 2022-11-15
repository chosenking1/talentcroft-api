<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'tags' => ['required', 'array'],
            'type' => ['required', 'string'],
            'category' => ['required', 'string'],
            'status' => ['nullable', 'string'],
            'release_date' => ['nullable'],
            'available_from' => ['nullable'],
            'available_to' => ['nullable'],
            'featured' => ['nullable'],
            'is_private' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
            'amount' => ['nullable'],
            'currency' => ['nullable'],
            'has_discount' => ['nullable','boolean'],
        ];
    }
}
