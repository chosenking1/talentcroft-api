<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required','string'],
            'last_name' => ['required','string'],
            'email' => ['required','string', 'max:255'],
            'password' => ['required','string', 'max:500'],
            'country_code' => ['required','integer'],
            'phone_number' => ['required','integer'],
            'user_type' => ['required','string'],
            'avatar' => ['required','string'],
            'location' => ['required','string'],
            'bio' => ['required','string'],
            'banner' => ['required','string'],
            'role_id' => ['required','integer']
        ];
    }
}
