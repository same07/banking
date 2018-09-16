<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    //
    protected $table = 'customer_accounts';
    protected $appends = ['total_saldo'];
    protected $hidden = ['pin'];

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
    /**
     * Customer Relation
     * 
     * @return Relation
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Transaction Relation
     * 
     * @return Relation
     */
    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Total Saldo
     * 
     * @return decimal
     */
    public function getTotalSaldoAttribute()
    {
        $balance = $this->balance();
        return 'Rp. ' .number_format($balance, 2, ',', '.');
    }

    public function balance()
    {
        $saldoDebit = $this->transactionDetail()->sum('debit');
        $saldoCredit = $this->transactionDetail()->sum('credit');
        return $saldoDebit - $saldoCredit;
    }
}
