<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\User;
use DB;
use App\Customer;
use App\Role;

class AuthenticateController extends Controller
{
	public function authenticate(Request $request)
	{
		$rules = [
			'email'    => 'required|email',
			'password' => 'required'
		];

		$this->validate($request, $rules);

		$credentials = $request->only('email', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'Invalid login credential'], 401);
			}
		} catch(JWTException $e) {
			return response()->json(['error' => 'Could not create token'], 500);
		}

		$user = $request->user();

		return response()->json(compact('token', 'user'));
	}

	public function register(Request $request)
	{
		$rules = [
			'email' => 'required|email|unique:users',
			'password' => 'required',
			'phone' => 'required',
			'address' => 'required',
			'name' => 'required'
		];
		$this->validate($request, $rules);

		DB::beginTransaction();
		try {
			$user = new User;
			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
			$user->save();
			
			$role = Role::where('slug', 'customer')
				->first();
			$user->roles()->attach($role);

			$customer = new Customer;
			$customer->name = $request->name;
			$customer->phone = $request->phone;
			$customer->address = $request->address;
			$user->customer()->save($customer);

			DB::commit();
			return response()->json('success');
		} catch (Exception $e) {
			DB::rollback();
			return response()->json(['error' => 'Something Went Wrong'], 500);
		}

		
	}
}