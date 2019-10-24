<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnAddressRequest extends FormRequest
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
	public function rules(Request $request)
	{
//	    print_r($request->all()); exit;
		return [
			'first_name' => 'required|string|min:4|max:100',
			'last_name' => 'required|string|min:4|max:100',
			'full_address' => 'required|max:150|',
			'phone_number' => 'nullable|numeric|digits_between:6,12',
			'street_city' => 'nullable|string|min:4|max:100',
			'province' => 'required',
			'country' => 'required',
			'zip_code' => 'required|numeric|digits_between:4,8',
			'default' => 'nullable'
		];
	}

	public function messages()
	{
		return [
			'email.required' => 'Email is required!',
			'email.unique' => 'Email already exists!',
			'user_name.required' => 'UserName is required!',
			'user_name.unique' => 'UserName already exists!',
			'phone.numeric' => 'Only Numbers are allowed',
			'city.alpha' => 'Please enter valid input',
			'province.alpha' => 'Please enter valid input',
			'country.alpha' => 'Please enter valid input',
			'postal_code.alpha_num' => 'Please enter valid input',
		];
	}
}
