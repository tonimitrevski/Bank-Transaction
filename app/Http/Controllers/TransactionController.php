<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportingCollection;
use App\Services\ReportingServices;

class TransactionController extends Controller
{
    /**
     * @var ReportingServices
     */
    private $reportingServices;

    /**
     * TransactionController constructor.
     * @param ReportingServices $reportingServices
     */
    public function __construct(ReportingServices $reportingServices)
    {
        $this->reportingServices = $reportingServices;
    }

    public function index(int $days = 7)
    {
        $date = $this->reportingServices->getDate($days)->toDateString();
        $reportings = $this->reportingServices->getReporting($date);

        return view('reporting', compact('reportings', 'date'));
    }

    /**
     * @param int $days
     * @return \App\Http\Resources\ApiException|ReportingCollection
     */
    public function get(int $days = 7)
    {
        return $this->reportingServices->getReportingApi($days);
    }

}
