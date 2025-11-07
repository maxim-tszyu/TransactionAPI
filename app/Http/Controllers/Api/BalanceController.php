<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class BalanceController extends Controller
{
    public function __construct(private TransactionService $service)
    {
    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        if (!$request->isJson()) {
            abort(400, 'Incorrect request:json expected.');
        }

        $transaction = $this->service->deposit($request);

        return response()->json([
            'status' => '200',
            'message' => 'Deposit successful',
            'transaction' => $transaction,
        ]);
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        if (!$request->isJson()) {
            abort(400, 'Incorrect request:json expected.');
        }

        $transaction = $this->service->withdraw($request);

        return response()->json([
            'status' => '200',
            'message' => 'Withdraw successful',
            'transaction' => $transaction,
        ]);
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        if (!$request->isJson()) {
            abort(400, 'Incorrect request:json expected.');
        }

        $transactions = $this->service->transfer($request);

        return response()->json([
            'status' => '200',
            'message' => 'Transfer successful',
            'transaction' => $transactions['transaction'],
            'related_transaction' => $transactions['related_transaction'],
        ]);
    }

    public function balance(int $user_id): JsonResponse
    {
        $user = User::find($user_id);
        if (!$user) {
            abort(404, 'User not found.');
        }

        return response()->json([
            'status' => '200',
            'user_id' => $user->id,
            'balance' => $user->balance?->balance ?? 'User does not have a balance.',
        ]);
    }
}
