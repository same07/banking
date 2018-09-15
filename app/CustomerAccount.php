<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    //
    protected $table = 'customer_accounts';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
