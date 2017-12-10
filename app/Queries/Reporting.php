<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 10.12.17
 * Time: 11:58
 */

namespace App\Queries;

class Reporting
{
    public static function get($time)
    {
        return \DB::table('transactions')
            ->select(
                [
                    \DB::raw("( SELECT code FROM `countries`
                  WHERE `id` = country_id) AS code"),
                    \DB::raw('COUNT(DISTINCT user_id) AS Unique_Customers'),
                    \DB::raw('COUNT(CASE WHEN type = 1 THEN id END) AS No_of_Deposits'),
                    \DB::raw('SUM(CASE WHEN type = 1 THEN amount END) AS deposit'),
                    \DB::raw('COUNT(CASE WHEN type = 0 THEN id END) AS No_of_withdraw'),
                    \DB::raw('SUM(CASE WHEN type = 0 THEN amount END) AS withdraw'),
                ]
            )
            ->where('created_at', '>', $time)
            ->groupBy('country_id')
            ->get();
    }
}
