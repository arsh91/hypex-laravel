<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaveBillingAddressRequest extends FormRequest
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
			'billing_full_address' => 'required',
			'billing_street_city' => 'required',
			'billing_country' => 'required',
			'billing_zip' => 'required',
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
