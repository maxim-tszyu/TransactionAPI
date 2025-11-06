<?php

namespace App\DTO;

use App\Models\Transaction;

class TransactionDTO
{
    public int $user_id;
    public float $amount;
    public ?string $comment;
    public string $type;

    public function __construct(Transaction $transaction)
    {
        $this->user_id = $transaction->user_id;
        $this->amount = (float)$transaction->amount;
        $this->comment = $transaction->comment;
        $this->type = $transaction->type->value ?? $transaction->type;
    }
}
