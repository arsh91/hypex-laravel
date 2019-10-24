<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandFormRequest extends FormRequest
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
                    'brand_name' => array(
                            "required",
                            Rule::unique('brands')->ignore($id),
                        ),
                    'select_brand_type' => 'required|array',
                    'select_brand_type.*' => 'required|string|distinct|min:3'
                ];
            }else{
                return [
                    'brand_name' => 'required|unique:brands,brand_name',
                    'select_brand_type' => 'required|array',
                    'select_brand_type.*' => 'required|string|distinct|min:3'
                ];
            }
        }else{
            return [
            
            ];
        } 
    }


    public function messages()
    {  
        $customMessages = array();
        $select_brand_type = Request::get('select_brand_type');
        if(isset($select_brand_type) && count($select_brand_type)){
            foreach ($select_brand_type as $key => $value) {
                $index = $key + 1;
                $customMessages['select_brand_type.'.$key.'.required'] = 'Brand type field is required at '.$index.' text box.';
                $customMessages['select_brand_type.'.$key.'.distinct'] = 'Brand type field has a duplicate value at '.$index.' text box.';
            }
        }
        return $customMessages;
    }
}
