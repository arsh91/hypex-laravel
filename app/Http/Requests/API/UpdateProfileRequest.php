<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
		$id = Auth::id();
        return [
            'first_name' => 'required|string|min:4|max:100',
			'last_name' => 'required|string|min:4|max:100',
			'email' => 'required|max:150|email|unique:users,email,'.$id,
			'user_name' => 'required|string|min:4|max:100|unique:users,user_name,'.$id,
			'phone' => 'nullable|numeric|digits_between:6,12',
			'city' => 'nullable|string|min:4|max:100',
			'state' => 'nullable|string|min:4|max:100',
			'country' => 'nullable|string|min:4|max:100',
			'postal_code' => 'nullable|numeric|digits_between:4,8',
			'shoe_size' => 'nullable',
			'streetwear_size' => 'nullable',
			'profile_pic_url' => 'nullable'
			
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
			'state.alpha' => 'Please enter valid input',
			'country.alpha' => 'Please enter valid input',
			'postal_code.alpha_num' => 'Please enter valid input',
        ];
    }
	
	

}
