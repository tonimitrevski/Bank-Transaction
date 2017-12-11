<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 10.12.17
 * Time: 09:08
 */

namespace App\Queries;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountException;
use App\Transaction;
use App\User;

class OptimisticLockingTransaction
{
    private $accountId;
    private $data;
    private $user;

    private $userData = [
        'balance' => 0,
    ];

    /**
     * TransactionOperation constructor.
     * @param $accountId
     * @param $data
     */
    public function __construct($accountId, $data)
    {
        $this->accountId = $accountId;
        $this->data = $data;
    }

    /**
     * @throws InvalidAccountException
     * @throws InsufficientBalanceException
     */
    public function update()
    {
        $account = User::whereId($this->accountId);
        if (! $account->exists()) {
            throw new InvalidAccountException('User not exist');
        }

        do {
            $this->user = $account->first();
            if (!$this->data['type'] && $this->user->balance < $this->data['amount']) {
                throw new InsufficientBalanceException('You do not have enough money in your account');
            }

            $this->prepareAmount($this->data['type']);
            $updated = $this->updateUser();
        } while (! $updated);

        $this->makeTransaction();
    }

    private function prepareAmount($type)
    {
        if ($type) {
            $this->userData['balance'] = $this->user->balance + $this->data['amount'];
            $this->userData['count_transaction'] = $this->user->count_transaction + 1;
            $this->checkTransactionIsThird();
            return;
        }

        $this->userData['balance'] = $this->user->balance - $this->data['amount'];
    }

    private function checkTransactionIsThird()
    {
        $divisible = $this->userData['count_transaction'] / 3;

        if ((int) $divisible === $divisible) {
            $this->userData['bonus'] = $this->calculateBonus();
        }
    }

    private function calculateBonus()
    {
        return $this->user->bonus + $this->calculatePercentage($this->user->bonus_param, $this->data['amount']);
    }

    private function updateUser()
    {
        return User::whereId($this->user->id)
            ->where('updated_at', '=', $this->user->updated_at)
            ->update($this->userData);
    }

    private function makeTransaction()
    {
        $transaction = new Transaction();
        $transaction->country_id = $this->data['country_id'];
        $transaction->user_id = $this->user->id;
        $transaction->amount = $this->data['amount'];
        $transaction->type = $this->data['type'];
        $transaction->save();
    }

    /**
     * @param $percentage
     * @param $totalWidth
     * @return float|int
     */
    private function calculatePercentage($percentage, $totalWidth)
    {
        return ($percentage / 100) * $totalWidth;
    }
}
