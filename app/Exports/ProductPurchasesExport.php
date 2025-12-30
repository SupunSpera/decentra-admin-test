<?php

namespace App\Exports;

use App\Models\ProductPurchase;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductPurchasesExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    private $startDate;
    private $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    /**
     * query
     *
     * @return void
     */
    public function query()
    {
        $query= ProductPurchase::query()->with(['customer', 'product']);

        // Apply filters if dates are provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        return $query;
    }
    /**
     * headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Customer Name',
            'Customer Email',
            'Product Name',
            'Product Price',
            'Purchase Date',
        ];
    }
    /**
     * @param ProductPurchase $purchase
     */
    public function map($purchase): array
    {
        return [
            $purchase->id,
            $purchase->customer->first_name . ' ' . $purchase->customer->last_name,
            $purchase->customer->email,
            $purchase->product->name,
            number_format($purchase->product->price, 2),
            $purchase->created_at->format('d/m/Y-H:i:s'),
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
