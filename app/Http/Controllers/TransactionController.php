<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    protected $transaction;
    public function __construct(TransactionRepository $transaction)
    {
        $this->transaction = $transaction;
    }
    /**
     * Deposit Amount
     * 
     * @return json
     */
    public function deposit(Request $request)
    {
        $rules = [
			'customer_account_id'  => 'required',
			'amount' => 'required|numeric|min:10000',
		];
        $this->validate($request, $rules);
        $return = $this->transaction->deposit($request->all());
        if ($return === 'success') {
            return response()->json('success');
        }
        return response()->json(['error' => $return], 500);
    }

    public function withdraw(Request $request)
    {
        $rules = [
			'customer_account_id'  => 'required',
			'amount' => 'required|numeric|min:0',
		];
        $this->validate($request, $rules);
        $return = $this->transaction->withdraw($request->all());
        if ($return === 'success') {
            return response()->json('success');
        }
        return response()->json(['error' => $return], 500);
    }

    public function transfer(Request $request)
    {
        $rules = [
			'customer_account_id'  => 'required',
            'amount' => 'required|numeric|min:0',
            'rekening_number' => 'required|numeric'
		];
        $this->validate($request, $rules);
        $return = $this->transaction->transfer($request->all());
        if ($return === 'success') {
            return response()->json('success');
        }
        return response()->json(['error' => $return], 500);
    }

    public function mutation(Request $request)
    {
        $rules = [
			'customer_account_id'  => 'required'
		];
        $this->validate($request, $rules);
        $data = $this->transaction->getMutation($request->customer_account_id);
        return response()->json($data);
    }

    public function checkAccount(Request $request)
    {
        $check = $this->transaction->checkAccountNumber($request->rekening_number);
        if ($check !== null) {
            return response()->json($check->customer->name);
        }
        return response()->json(['error' => 'Account number not found'], 500);
    }

    public function getSaldoBank()
    {
        $saldo = $this->transaction->totalSaldoBank();
        return response()->json($saldo);
    }
}
