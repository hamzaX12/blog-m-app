<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // wesh mean that if you have the right to execute the class 
        // return false;
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
            'title'=>'required|min:4|max:100', 
            'content'=>'required',
            'picture' => 'image|mimes:png,jpeg,jpg,gif,svg,webp|max:1024'                        
            // 'picture' => 'image|mimes:png,jpeg,jpg,gif,svg,webp|max:1024|dimensions:min_height=500,min_width'                        
        ];
    }
}
// 1024 kbait => 1 mega