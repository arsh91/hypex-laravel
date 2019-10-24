<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategorgFormRequest extends FormRequest
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
                    'category_name' => array(
                            "required",
                            Rule::unique('categories')->ignore($id),
                        ),
                    'select_brand' => 'required|array',
	                'category_image' => 'nullable|mimes:jpeg,bmp,png,jpg',
                ];
            }else{
                return [
                    'category_name' => 'required|unique:categories,category_name',
                    'select_brand' => 'required|array',
	                'category_image' => 'nullable|mimes:jpeg,bmp,png,jpg',
                ];
            }
            
        }else{
            return [
            
            ];
        } 
    }
}
