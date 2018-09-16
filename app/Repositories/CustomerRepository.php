<?php

namespace App\Repositories;

use Czim\Repository\BaseRepository;
use App\Customer;
use App\CustomerAccount;
use Carbon\Carbon;
use DB;
class CustomerRepository extends BaseRepository
{
    /**
     * Returns specified model class name.
     *
     * @return string
     */
    public function model()
    {
        return Customer::class;
    }

    public function createAccount($id, $data)
    {
        DB::beginTransaction();
        try {
            $account = new CustomerAccount;
            $account->status = 'pending';
            $account->card_number = $this->generateCardNumber();
            $account->rekening_number = $this->generateAccountNumber();
            $account->account_type_id = $data['account_type_id'];
            $account->expired_date = Carbon::now()->addYears(5)->format('Y-m-d');
            $account->customer_id = $id;
            $account->pin = $data['pin'];
            $account->save();
            DB::commit();
            return ($account) ? true : false;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
        
    }

    public function blockAccount($id)
    {
        $account = CustomerAccount::find($id);
        $account->status = 'blocked';
        $account->save();
        return true;
    }

    public function activeAccount($id)
    {
        $account = CustomerAccount::find($id);
        $account->status = 'active';
        $account->save();
        return true;
    }

    public function generateCardNumber()
    {
        $flag = true;
        while ($flag) {
            $number = $this->generateNumber(16);
            $flag = $this->checkCardNumber($number) !== null ? true : false;
        }
        return $number;
    }

    public function checkCardNumber($cardNumber)
    {
        $account = CustomerAccount::where('card_number', $cardNumber)
            ->first();
        return $account;
    }

    public function generateAccountNumber()
    {
        $flag = true;
        while ($flag) {
            $number = $this->generateNumber(12);
            $flag = $this->checkAccountNumber($number) !== null ? true : false;
        }
        return $number;
    }

    public function checkAccountNumber($cardNumber)
    {
        $account = CustomerAccount::where('rekening_number', $cardNumber)
            ->first();
        return $account;
    }

    public function generateNumber($length)
    {
        $number = rand(1, 9);
        while (strlen($number) < $length) {
            $number .= rand(0, 9);
        }

        return $number;
    }
}
