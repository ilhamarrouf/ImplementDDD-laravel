<?php

namespace App\Domain\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|max:32|confirmed',
        ];
    }
}
