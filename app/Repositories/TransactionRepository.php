<?php

namespace App\Repositories;

use Czim\Repository\BaseRepository;
use App\Transaction;
use App\TransactionDetail;
use App\CustomerAccount;
use Illuminate\Support\Str;
use DB;
use Exception;
use Auth;

class TransactionRepository extends BaseRepository
{
    /**
     * Returns specified model class name.
     *
     * @return string
     */
    public function model()
    {
        return Transaction::class;
    }

    public function isAccountActive($id)
    {
        return CustomerAccount::where('id', $id)
            ->where('status', 'active')
            ->first();
    }

    public function deposit($data)
    {
        if ($this->isAccountActive($data['customer_account_id']) === null) {
            return 'Account is not active';
        }
        DB::beginTransaction();
        try {
            $transaction = new Transaction;
            $transaction->number = $this->generateNumber();
            $transaction->date = Date('Y-m-d');
            $transaction->save();

            $this->debitTransaction($transaction->id, $data['amount'], $data['customer_account_id']);

            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return 'Something Went Wrong';
        }
    }

    public function withdraw($data)
    {
        if ($this->isAccountActive($data['customer_account_id']) === null) {
            return 'Account is not active';
        }
        DB::beginTransaction();
        try {
            $transaction = new Transaction;
            $transaction->number = $this->generateNumber();
            $transaction->date = Date('Y-m-d');
            $transaction->save();
            $credit = $this->creditTransaction($transaction->id, $data['amount'], $data['customer_account_id']);

            if ($credit === false) {
                return 'Your balance is not enough. Please Check your balance account';
            }

            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return 'Something Went Wrong';
        }
    }

    public function transfer($data)
    {
        $customerAccount = $this->checkAccountNumber($data['rekening_number']);
        if ($this->isAccountActive($data['customer_account_id']) === null || $this->isAccountActive($customerAccount->id)) {
            return 'Account is not active';
        }
        DB::beginTransaction();
        try {
            
            $transaction = new Transaction;
            $transaction->number = $this->generateNumber();
            $transaction->date = Date('Y-m-d');
            $transaction->save();
            $credit = $this->creditTransaction($transaction->id, $data['amount'], $data['customer_account_id']);

            if ($credit === false) {
                return 'Your balance is not enough. Please check your balance account';
            }
            
            $debit = $this->debitTransaction($transaction->id, $data['amount'], $customerAccount->id);
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return 'Something Went Wrong';
        }
    }

    public function getMutation($customerAccountId)
    {
        $customerId = Auth::user()->customer->id;
        $account = CustomerAccount::where('id', $customerAccountId)
            ->where('customer_id', $customerId)
            ->with(
                [
                    'transactionDetail'
                ]
            )
            ->first();
        return $account;
    }

    public function checkAccountNumber($rekeningNumber)
    {
        $customer = CustomerAccount::where('rekening_number', $rekeningNumber)
            ->with('customer')->first();
        return $customer !== null ? $customer : null;
    }

    protected function generateNumber()
    {
        $flag = false;
        while (!$flag) {
            $number = Str::random(35);
            $flag = $this->isNumberAvailable($number);
        }
        return $number;
    }

    protected function isNumberAvailable($number)
    {
        $check = $this->model->where('number', $number)
            ->first();
        return $check === null ? true : false;
    }

    public function debitTransaction($transactionId, $amount, $customerAccountId)
    {
        $transactionDetails = new TransactionDetail;
        $transactionDetails->transaction_id = $transactionId;
        $transactionDetails->debit = $amount;
        $transactionDetails->credit = 0;
        $transactionDetails->customer_account_id = $customerAccountId;
        $transactionDetails->save();
    }

    public function creditTransaction($transactionId, $amount, $customerAccountId)
    {
        $canWithdraw = $this->canWithdraw($customerAccountId, $amount);

        if ($canWithdraw === false) {
            return false;
        }
        $transactionDetails = new TransactionDetail;
        $transactionDetails->transaction_id = $transactionId;
        $transactionDetails->debit = 0;
        $transactionDetails->credit = $amount;
        $transactionDetails->customer_account_id = $customerAccountId;
        $transactionDetails->save();
    }

    public function canWithdraw($customerAccountId, $amount)
    {
        $account = CustomerAccount::find($customerAccountId);
        return $account->balance() >= $amount;
    }

    public function totalSaldoBank()
    {
        $balance = $this->getBalanceBank();
        return 'Rp. ' .number_format($balance, 2, ',', '.');
    }

    public function getBalanceBank()
    {
        $debit = TransactionDetail::sum('debit');
        $credit = TransactionDetail::sum('credit');
        return $debit - $credit;
    }
}
