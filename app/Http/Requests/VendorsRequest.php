<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorsRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png|image',
            'name' => 'required|string|max:100',
            'mobile' => 'required|max:100|unique:vendors,mobile,'.$this -> id,
            'email'  => 'required|nullable|email|max:100|unique:vendors,email,'.$this -> id,
            'category_id' => 'required|exists:main_categories,id',
            'address'   => 'required|string|max:500',
            'password'  => 'required_without:id',

        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'string' => 'نوع الحقل يجب ان يكون حروف او حروف وارقام',
            'required_without' => 'هذا الحقل مطلوب',
            'max' => 'هذا الحقل يجب ان لا يذيد عن 100 حرف',
            'address.max' => 'هذا الحقل يجب ان لا يذيد عن 500 حرف',
            'category.exists' => 'التصنيف الرئيسي غير موجود',
            'email.email'  => 'الرجاء ادخال بريد الكتروني صالح',
            'logo.mimes' => 'امتداد الصورة المسموح به jpg,jpeg,png  فقط',
            'logo.image'  => 'يجب اختيار صورة',
            'email.unique' => 'هذا البريد موجود مسبقا',
            'mobile.unique' => 'عفوا هذا الرقم موجود مسبقا'
        ];
    }
}
