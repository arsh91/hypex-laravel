<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class ExcelFileValidation extends FormRequest
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
            return [
                'file' => 'required|file',
            ];
        }else{
            return [
            
            ];
        } 
    }

    public function messages()
    {
        return [
            'file.required' => 'Excel File is required.',
            'file.mimes' => 'Excel File must be in given format like xlsx,xls,ods.',
        ];
    }
}
