<?php

namespace Tests\Unit;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountException;
use App\Queries\OptimisticLockingTransaction;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MakeTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws InsufficientBalanceException
     */
    public function testUserIsNotExist()
    {
        $this->withExceptionHandling();

        factory(User::class)->create();

        $data = [
            'amount' => 100,
            'type' => 1
        ];

        try {
            (new OptimisticLockingTransaction(2, $data))->update();
        } catch (InvalidAccountException $e) {
            $this->assertEquals($e->getMessage(), 'User not exist');
        }
    }

    /**
     * @throws InvalidAccountException
     */
    public function testUserHasNotHaveAmount()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'balance' => 50,
        ]);

        $data = [
            'amount' => 100,
            'type' => 0
        ];

        try {
            (new OptimisticLockingTransaction($user->id, $data))->update();
        } catch (InsufficientBalanceException $e) {
            $this->assertEquals($e->getMessage(), 'You do not have enough money in your account');
        }
    }

    /**
     * @throws InsufficientBalanceException
     * @throws InvalidAccountException
     */
    public function testAddAmount()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'balance' => 50,
        ]);

        $data = [
            'amount' => 100,
            'type' => 1,
            'country_id' => 4
        ];

        (new OptimisticLockingTransaction($user->id, $data))->update();

        $this->assertEquals($user->fresh()->balance, 150);
        $this->assertEquals($user->fresh()->count_transaction, 1);
    }

    /**
     * @throws InsufficientBalanceException
     * @throws InvalidAccountException
     */
    public function testRemoveAmount()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'balance' => 50,
        ]);

        $data = [
            'amount' => 25,
            'type' => 0,
            'country_id' => 4
        ];

        (new OptimisticLockingTransaction($user->id, $data))->update();

        $this->assertEquals($user->fresh()->balance, 25);
        $this->assertEquals($user->fresh()->count_transaction, 0);
    }

    /**
     * @throws InsufficientBalanceException
     * @throws InvalidAccountException
     */
    public function testAddBonus()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'balance' => 50,
            'count_transaction' => 2,
        ]);

        $data = [
            'amount' => 25,
            'type' => 1,
            'country_id' => 4
        ];

        (new OptimisticLockingTransaction($user->id, $data))->update();

        $bonusAdded = $this->calculatePercentage($user->bonus_param, $data['amount']);

        $this->assertEquals($user->fresh()->balance, 75);
        $this->assertEquals($user->fresh()->count_transaction, 3);
        $this->assertEquals($user->fresh()->bonus, $user->bonus + $bonusAdded);
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
