<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user)
            ],
            'role' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:active,inactive',
        ];
    }
}
