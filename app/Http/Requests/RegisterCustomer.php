<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomer extends FormRequest
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'customer_type_id' => ['required', Rule::in(['1', '2', '3', '4'])],
            'email_primary' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
    }
}
