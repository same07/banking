<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    //
    protected $table = 'transaction_details';
    protected $fillable = ['debit', 'credit', 'customer_account_id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    
}
