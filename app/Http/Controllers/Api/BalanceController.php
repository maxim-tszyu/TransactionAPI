<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function __construct(private TransactionService $service)
    {
    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        $transaction = $this->service->deposit($request);

        return response()->json([
            'message' => 'Deposit successful',
            'transaction' => $transaction,
        ]);
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $transaction = $this->service->withdraw($request);

        return response()->json([
            'message' => 'Withdraw successful',
            'transaction' => $transaction,
        ]);
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        $transactions = $this->service->transfer($request);

        return response()->json([
            'message' => 'Transfer successful',
            'transaction' => $transactions['transaction'],
            'related_transaction' => $transactions['related_transaction'],
        ]);
    }

    public function balance(Request $request, int $user_id): JsonResponse
    {
        $user = User::findOrFail($user_id);
        $balance = $user->balance?->balance ?? 0;

        return response()->json([
            'user_id' => $user->id,
            'balance' => $balance,
        ]);
    }
}
