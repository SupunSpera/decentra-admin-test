<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\ProjectInvestment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShortTermFundingExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
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
        $query = ProjectInvestment::query()
            ->join('projects', 'projects.id', '=', 'project_investments.project_id')
            ->with(['customer', 'project'])
            ->where('projects.type', Project::TYPE['SHORT_TERM']);

        // Apply date filters if dates are provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('project_investments.created_at', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->whereDate('project_investments.created_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->whereDate('project_investments.created_at', '<=', $this->endDate);
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
            'Project Name',
            'Invested Amount',
            'Invested Date',
        ];
    }
    /**
     * @param ProjectInvestment $projectInvestment
     */
    public function map($projectInvestment): array
    {
        return [
            $projectInvestment->id,
            $projectInvestment->customer->first_name . ' ' . $projectInvestment->customer->last_name,
            $projectInvestment->customer->email,
            $projectInvestment->project->name,
            number_format($projectInvestment->amount, 2),
            $projectInvestment->created_at->format('d/m/Y-H:i:s'),
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
