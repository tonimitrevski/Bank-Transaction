<?php

namespace Tests\Feature;

use App\Queries\OptimisticLockingTransaction;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserInSameTimeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \App\Exceptions\InsufficientBalanceException
     * @throws \App\Exceptions\InvalidAccountException
     * @throws \Exception
     */
    public function testUpdateInSameTime()
    {
        $user = factory(User::class)->create();

        $data1 = [
            'amount' => 25,
            'type' => 1,
            'country_id' => 4
        ];

        $data2 = [
            'amount' => 100,
            'type' => 1,
            'country_id' => 4
        ];

        DB::beginTransaction();

        (new OptimisticLockingTransaction($user->id, $data1))->update();
        (new OptimisticLockingTransaction($user->id, $data2))->update();

        DB::commit();

        $user = $user->fresh();

        $this->assertEquals($user->fresh()->balance, 125);
        $this->assertEquals($user->fresh()->count_transaction, 2);
    }
}
