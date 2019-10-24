<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|min:4|max:100',
			'last_name' => 'required|string|min:4|max:100',
			'user_name' => 'required|unique:users,user_name|string|min:4|max:100',
			'email' => 'required|email|unique:users,email|max:100',
			'password' => 'required|min:6|max:255',
			
        ];
    }
	
	
	
	public function messages()
    {
        return [
            'email.required' => 'Email is required!',
			'email.unique' => 'Email already exists!',
            'user_name.unique' => 'UserName is already taken!',
            'password.required' => 'Password is required!'
        ];
    }
}
