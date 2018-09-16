<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
	public function show()
	{
		$user = Auth::user();
		$user->customer = $user->customer;
		$user->roles = $user->roles;
		return response()->json($user);
	}

	public function getAccounts()
	{
		$user = Auth::user();
		$accounts = $user->customer->accounts()->get();
		return $accounts;
	}

	public function checkPassword(Request $request)
	{
		$user = Auth::user();
        $check = Auth::guard('web')->attempt(
            [
                'email' => $user->email,
                'password' => $request->password
            ]
        );
        
        if ($check) {
			return response()->json(['message' => 'success']);
		}
		return response()->json(['error' => 'Wrong Password'], 500);
	}

	public function updateProfile(Request $request)
	{
		$rules = [
			'name'  => 'required',
			'email' => 'required|email|',
		];

		$this->validate($request, $rules);

		$user = $request->user();
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->save();

		return response()->json(compact('user'));
	}

	public function updatePassword(Request $request)
	{
		$rules = [
			'new_password'         => 'required',
			'confirm_new_password' => 'required|same:new_password'
		];

		$this->validate($request, $rules);

		$user = $request->user();
		$user->password = bcrypt($request->input('new_password'));
		$user->saveOrFail();

		return response()->json(compact('user'));
	}
}