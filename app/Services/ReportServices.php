<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 10.12.17
 * Time: 18:03
 */

namespace App\Services;


use App\Http\Resources\ApiException;
use App\Http\Resources\ReportingCollection;
use App\Queries\Report;
use App\Utilities\CustomLogger\LogMsg;
use Carbon\Carbon;

class ReportServices
{
    public function getReportingApi($days)
    {
        try {
            return new ReportingCollection($this->prepareApiObject($days));
        } catch (\Exception $exception) {
            $exception->errorCode = (app()->make(LogMsg::class))
                ->error(__METHOD__, $exception, 'reporting');
            return new ApiException($exception);
        }
    }


    public function getReporting($time)
    {
        return Report::get($time);
    }

    public function getDate($days)
    {
        return Carbon::now()->subDays($days);
    }

    /**
     * @param $days
     * @return array|\Illuminate\Support\Collection
     */
    private function prepareApiObject($days)
    {
        $time = $this->getDate($days)->toDateString();
        $data = Report::get($time);
        $data[] = $time;

        return $data;
    }
}