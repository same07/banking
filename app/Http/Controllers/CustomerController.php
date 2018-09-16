<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;
use App\Customer;
use App\CustomerAccount;
use Auth;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
    }

    public function account()
    {
        $data = CustomerAccount::whereIn('status', ['active', 'blocked'])
            ->with(
                [
                    'type',
                    'customer'
                ]
            )->get();
        return response()->json($data);
    }

    public function totalCustomer()
    {
        return response()->json(Customer::count());
    }

    public function getCustomer()
    {
        return response()->json(Customer::all());
    }
    
    public function getAccountRequest()
    {
        $data = CustomerAccount::where('status', 'pending')
            ->with(
                [
                    'type',
                    'customer'
                ]
            )->get();
        return response()->json($data);
    }

    public function approveAccount(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];
        $this->validate($request, $rules);
        return ($this->customer->activeAccount($request->id)) ? 
            response()->json('success') :
            response()->json(['error' => 'Something Went Wrong'], 500);

    }

    public function blockAccount(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];
        $this->validate($request, $rules);
        return ($this->customer->blockAccount($request->id)) ? 
            response()->json('success') :
            response()->json(['error' => 'Something Went Wrong'], 500);
    }

    public function requestAccount(Request $request)
    {
        $rules = [
            'account_type_id' => 'required',
            'pin' => 'required|digits:6|numeric'
        ];
        $this->validate($request, $rules);
        $id = Auth::user()->customer->id;
        $this->customer->createAccount($id, $request->all());
        return response()->json('success');
    }
}
