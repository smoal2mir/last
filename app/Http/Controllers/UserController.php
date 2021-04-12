<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\User;

class UserController extends Controller {

	public function create(CreateUserRequest $request) {

		$user = User::create([
			"name" => htmlspecialchars($request->input("name")),
			"password" => bcrypt($request->input("password")),
			"country" => htmlspecialchars($request->input("country")),
			"device" => htmlspecialchars($request->input("device")),
			"ip" => $request->input("ip")
		]);

		if($user) {
			return response(["error" => false, "user" => $user, "message" => "user created successfully"], 200);
		} else {
			return response(["error" => true, "user" => null, "message" => "failed to create user"], 400);
		}

	}

}
