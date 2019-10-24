<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserAllDetailRequest extends FormRequest
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
        if(Request::isMethod('post')){
            $id = $request->id;
            if(isset($id)){
                return [
                    'email' => array(
                            "required",
                            "email",
                            Rule::unique('users')->ignore($id),
                        ),
                    'first_name' => 'required|string|min:4|max:100',
                    'last_name' => 'required|string|min:4|max:100',
                    'user_name' => array(
                            "required",
                            "string",
                            "max:100",
                            Rule::unique('users')->ignore($id),
                        ),
                    'phone' => 'nullable|numeric|digits_between:6,12',
                    'city' => 'nullable|string|min:4|max:100',
                    'state' => 'nullable|string|min:4|max:100',
                    'country' => 'nullable|string|min:4|max:100',
                    'postal_code' => 'nullable|numeric|digits_between:4,8',
                    'status' => 'required|numeric|in:1,2',
                ];
            }else{
                return [
                ];
            }
            
        }else{
            return [
            ];
        } 
    }
}
