<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeUserPassword extends FormRequest
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
			"password" => ["string", "min:6"],
			"user_id" => [Rule::exists("users", "id")],
			"adminHash" => ["string", Rule::in([env("ADMIN_HASH")])]
		];
    }

	public function messages()
	{
		return [
			"password.string" => "الرجاء تحديد كلمة المرور",
			"password.min" => "كلة المرور لا يجب أن تقل عن 6 أحرف",
			"user_id.exists" => "لم يتم التعرف على هدا المستخدم, الرجاء التأكد من صحة المدخلات",
			"adminHash.string" => "error_admin_hash_string",
			"adminHash.in" => "error_admin_hash_in",
		];
	}

}
