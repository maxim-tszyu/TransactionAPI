<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function deposit(DepositRequest $request)
    {
        return 'test';
    }

    public function withdraw(WithdrawRequest $request)
    {
        return 'test';
    }

    public function transfer(TransferRequest $request)
    {
        return 'test';
    }

    public function balance(Request $request, int $user_id)
    {
        return $user_id;
    }
}
