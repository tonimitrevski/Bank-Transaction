<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportingCollection;
use App\Services\ReportServices;

class TransactionController extends Controller
{
    /**
     * @var ReportServices
     */
    private $reportServices;

    /**
     * TransactionController constructor.
     * @param ReportServices $reporServices
     */
    public function __construct(ReportServices $reporServices)
    {
        $this->reportServices = $reporServices;
    }

    public function index(int $days = 7)
    {
        $date = $this->reportServices->getDate($days)->toDateString();
        $reportings = $this->reportServices->getReporting($date);

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
