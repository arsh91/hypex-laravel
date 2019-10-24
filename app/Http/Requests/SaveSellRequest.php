<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;

class SaveSellRequest extends FormRequest
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
            'bid_price' => 'required|numeric|min:1',
			'bid_days' => 'required',
            
            
            'shipping_first_name' => 'required',
			'shipping_last_name' => 'required',
			'shipping_phone' => 'required',
			'shipping_province' => 'required',
            
            
			'shipping_full_address' => 'required',
			'shipping_street_city' => 'required',
			'shipping_country' => 'required',
			'shipping_zip' => 'required',
            
            'billing_first_name' => 'required',
			'billing_last_name' => 'required',
			'billing_phone' => 'required',
			'billing_province' => 'required',
            
            
			'billing_full_address' => 'required',
			'billing_street_city' => 'required',
			'billing_country' => 'required',
			'billing_zip' => 'required',
			'hiddenSizeId' => 'required',
			'hiddenPrice' => 'required',
			'hiddenProdId' => 'required',
			'length' => 'required',
			'width' => 'required',
			'height' => 'required',
			'weight' => 'required',
	        'hiddenCurrency' => 'required',
			
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
