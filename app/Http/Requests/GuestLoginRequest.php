<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuestLoginRequest extends FormRequest
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
			"ip" => ["string", "ip"],
			"device" => ["string"],
			"adminHash" => ["string", Rule::in([env("ADMIN_HASH")])]
        ];
    }

	public function messages()
	{
		return [
			"name.string" => "الرجاء تحديد إسم المستخدم",
			"name.unique" => "هدا الإسم محجوز, الرجاء إختيار إسم أخر",
			"name.min" => "إسم المستخدم لا يجب أن يقل عن حرفين",
			"adminHash.string" => "error_admin_hash_string",
			"adminHash.in" => "error_admin_hash_in",
		];
	}


}
