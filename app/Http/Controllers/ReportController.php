<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use domain\Facades\ReportFacade;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('pages.reports.index');

    }

    /**
     * export
     *
     * @param  mixed $report_id
     * @return void
     */
    public function export(Request $request,$reportId)
    {
        $startDate = null;
        $endDate = null;

        if (isset($request['start_date'])) {
            $startDate = Carbon::parse($request['start_date'])->startOfDay();
        }

        if (isset($request['end_date'])) {
            $endDate = Carbon::parse($request['end_date'])->endOfDay();
        }

        $fileName = $reportId.'_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        return ReportFacade::exportReport($reportId,$startDate,$endDate,$fileName);
    }


    /**
     * view
     *
     * @param  mixed $request
     * @param  mixed $reportId
     * @return void
     */
    public function view(Request $request,$reportId)
    {
        $startDate = null;
        $endDate = null;

        if (isset($request['start_date'])) {
            $startDate = Carbon::parse($request['start_date'])->startOfDay();
        }

        if (isset($request['end_date'])) {
            $endDate = Carbon::parse($request['end_date'])->endOfDay();
        }

        return view('pages.reports.view',compact('startDate','endDate','reportId'));

    }
}
