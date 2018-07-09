<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            //'to.0.name' => 'required|string|max:255',
            'user.*' => 'required|string|email|max:255|unique:users',
            'user.*.password' => 'required|min:3|confirmed',
            'user.*.password_confirmation' => 'required|min:3|same:password',
            //'to.0.avatar' => 'image'
        ];
    }
}
