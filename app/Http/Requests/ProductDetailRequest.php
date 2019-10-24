<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductDetailRequest extends FormRequest
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
                    'product_name' => array(
                            'required',
                            'string',
                            'min:4',
                            'max:100',
                            Rule::unique('products')->ignore($id),
                        ),
                    'product_nick_name' => 'nullable|string|min:4|max:100',
                    'category_name' => 'required|string',
                    'brand_name' => 'required|string',
                    'brand_type' => 'required|string',
                    'style' => 'nullable|string|min:3|max:100',
                    'size' => 'required|array',
                    'size.*' => 'required|string',
                    'size_type' => 'required',
                    'color' => 'nullable|string|min:3|max:100',
                    'season' => 'nullable|string|min:3|max:100',
                    'currency_code' => 'required|string|min:3|max:100',
                    'retail_price' => 'required|string|min:2|max:100',
                    'description' => 'nullable|string|min:4|max:255',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|mimes:jpeg,bmp,png,jpg',
                    'othere_img' => 'nullable|array',
                    'othere_img.*' => 'nullable|mimes:jpeg,bmp,png,jpg',
                    'release_date' => 'nullable|date|date_format:Y-m-d',
                    'start_counter' => 'required|numeric',
                    'pass_code' => 'nullable|string',
                    'pass_value' => 'nullable|string|min:2|max:100',
                    'vip_status' => 'required|in:1,0',
                    'scrapped_status' => 'nullable|in:1,0',
                    'trending' => 'required|in:1,2',
                    'status' => 'required|in:1,2'
                    
                ];
            }else{
                return [
                    'product_name' => 'required|string|min:4|max:100|unique:products,product_name',
                    'product_nick_name' => 'nullable|string|min:4|max:100',
                    'category_name' => 'required|string',
                    'brand_name' => 'required|string',
                    'brand_type' => 'required|string',
                    'style' => 'nullable|string|min:3|max:100',
                    'size' => 'required|array',
                    'size.*' => 'required|string',
                    'size_type' => 'required',
                    'color' => 'nullable|string|min:3|max:100',
                    'season' => 'required|string|min:3|max:100',
                    'currency_code' => 'required|string|min:3|max:100',
                    'retail_price' => 'required|string|min:2|max:100',
                    'description' => 'required|string|min:4|max:255',
                    'img' => 'required|array',
                    'img.*' => 'nullable|mimes:jpeg,bmp,png,jpg',
                    'othere_img' => 'nullable|array',
                    'othere_img.*' => 'nullable|mimes:jpeg,bmp,png,jpg',
                    'release_date' => 'nullable|date|date_format:Y-m-d',
                    'start_counter' => 'required|numeric',
                    'pass_code' => 'nullable|string',
                    'pass_value' => 'nullable|string|min:2|max:100',
                    'vip_status' => 'required|in:1,0',
                    'scrapped_status' => 'nullable|in:1,0',
                    'trending' => 'required|in:1,2',
                    'status' => 'required|in:1,2'
                ];
            }
            
        }else{
            return [
            ];
        } 
    }
}
