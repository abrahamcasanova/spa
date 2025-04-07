<?php

namespace App\Http\Requests;

use App\Enums\UserRoles;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => [
                'required',
                'string',
                Rule::in([
                    UserRoles::admin(),
                    UserRoles::user(),
                    UserRoles::partner(),
                    UserRoles::other(),
                ]),
            ],
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
