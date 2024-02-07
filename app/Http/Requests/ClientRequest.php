<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:clients,email',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'The name field is required.',
            'name.max'         => 'The name field must not exceed 255 characters.',
            'email.required'   => 'The email field is required.',
            'email.email'      => 'The email format is invalid.',
            'email.unique'     => 'The email is already in use.',
            'phone.required'   => 'The phone field is required.',
            'phone.max'        => 'The phone field must not exceed 20 characters.',
            'address.required' => 'The address field is required.',
            'address.max'      => 'The address field must not exceed 255 characters.',
        ];
    }
}
