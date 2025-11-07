<?php

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Enums\TransactionEnum;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function deposit(DepositRequest $request): TransactionDTO
    {
        $data = $request->validated();
        $transaction = DB::transaction(function () use ($data) {
            $user = User::find($data['user_id']);
            if (!$user) {
                abort(404, 'User not found.');
            }
            $balance = Balance::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );
            $balance->increment('balance', $data['amount']);

            return Transaction::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
                'type' => TransactionEnum::DEPOSIT,
            ]);
        });

        return new TransactionDTO($transaction);
    }

    public function withdraw(WithdrawRequest $request): TransactionDTO
    {
        $data = $request->validated();
        $transaction = DB::transaction(function () use ($data) {
            $user = User::find($data['user_id']);
            if (!$user) {
                abort(404, 'User not found.');
            }

            $user->balance ? $balance = $user->balance : abort(409, 'User does not have a balance.');

            if ($balance->balance < $data['amount']) {
                abort(409, 'Not enough balance.');
            }

            $balance->decrement('balance', $data['amount']);

            return Transaction::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
                'type' => TransactionEnum::WITHDRAW,
            ]);
        });

        return new TransactionDTO($transaction);
    }

    public function transfer(TransferRequest $request): array
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            $user_from = User::find($data['from_user_id']);
            if (! $user_from) {
                abort(404, 'User not found.');
            }
            $user_to = User::find($data['to_user_id']);
            if (! $user_to) {
                abort(404, 'User not found.');
            }

            $user_from->balance ? $balance_from = $user_from->balance : abort(409, 'User does not have a balance.');
            if ($balance_from->balance < $data['amount']) {
                abort(409, 'Not enough balance.');
            }

            $balance_to = Balance::firstOrCreate(
                ['user_id' => $user_to->id],
                ['balance' => 0]
            );

            $balance_from->decrement('balance', $data['amount']);
            $balance_to->increment('balance', $data['amount']);

            $transaction_out = Transaction::create([
                'user_id' => $user_from->id,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
                'type' => TransactionEnum::TRANSFER_OUT,
            ]);

            $transaction_in = Transaction::create([
                'user_id' => $user_to->id,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
                'type' => TransactionEnum::TRANSFER_IN,
            ]);

            return [
                'transaction' => new TransactionDTO($transaction_out),
                'related_transaction' => new TransactionDTO($transaction_in),
            ];
        });
    }
}
