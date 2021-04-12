<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemberLoginRequest extends FormRequest
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
			"name" => ["string", "min:2"],
			"password" => ["string", "min:1"],
			"ip" => ["string", "ip"],
			"device" => ["string"],
			"adminHash" => ["string", Rule::in([env("ADMIN_HASH")])]
		];
	}

	public function messages()
	{
		return [
			"name.string" => "الرجاء تحديد إسم المستخدم",
			"name.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
			"password.string" => "الرجاء إدخال كلمة المرور",
			"password.min" => "كلمة المرور لا يجب أن تقل عن حرف واحد",
			"adminHash.string" => "error_admin_hash_string",
			"adminHash.in" => "error_admin_hash_in",
		];
	}

}
