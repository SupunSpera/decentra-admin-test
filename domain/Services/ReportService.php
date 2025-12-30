<?php

namespace domain\Services;

use App\Exports\LongTermFundingExport;
use App\Exports\ProductPurchasesExport;
use App\Exports\ShortTermFundingExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportService
{


    public function __construct() {}
    public function exportReport($reportId, $startDate = null, $endDate = null, $fileName)
    {
        switch ($reportId) {
            case 'product_purchases':
                return Excel::download(new ProductPurchasesExport($startDate, $endDate), $fileName);
                break;

            case 'short_term_funding':
                return Excel::download(new ShortTermFundingExport($startDate, $endDate), $fileName);
                break;

            case 'long_term_funding':
                return Excel::download(new LongTermFundingExport($startDate, $endDate), $fileName);
                break;

            default:
                abort(404, 'Report not found');
                break;
        }
    }
}
