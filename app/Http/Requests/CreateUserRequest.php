<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            "name" => ["string", Rule::unique("users", "name"), "min:2"],
            "password" => "string",
            "country" => "string",
            "device" => "string",
            "ip" => "ip",
			"adminHash" => ["string", Rule::in([env("ADMIN_HASH")])]
        ];
    }

    public function messages()
	{
		return [
			"name.string" => "الرجاء تحديد إسم المستخدم",
			"name.unique" => "هدا الإسم محجوز, الرجاء إختيار إسم أخر",
			"name.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
			"password.string" => "الرجاء إختيار كلمة المرور",
			"country.string" => "error_country_string",
			"device.string" => "error_device_stirng",
			"ip.ip" => "error_ip",
			"adminHash.string" => "error_admin_hash_string",
			"adminHash.in" => "error_admin_hash_in",
		];
	}

}
