<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
            //'active' => 'required|in:0,1',
            'direction' => 'required|in:rtl,ltr'

        ];
    }

    public function messages(){

        return[
        'required' => 'هذا الحقل مطلوب', 'in' => 'عفوا القيمة المدخلة غير صحيحة',
         'name.string' => 'يسمح بادخال الاحرف فقط',
         'name.max'    => 'يسمح بادخال 100 حرف كحد افصى',
            'abbr.string' => 'يسمح بادخال الاحرف فقط',
            'abbr.max'   => 'يسمح بادخال 10 احرف كحد اقصى',

        ];
    }

}
