<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountType;
class AccountTypeController extends Controller
{
    //
    public function index()
    {
        return response()->json(AccountType::all());
    }
}
