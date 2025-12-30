<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use Livewire\Component;

class ReportsTable extends Component
{
    public $startDate, $endDate;
    public $reports = [];

    public function mount()
    {
        $this->loadReportsNames();
    }

    public function render()
    {
        return view('pages.reports.components.reports-table');
    }

    /**
     * Define validation rules.
     */
    protected function rules()
    {
        return [
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
        ];
    }

    /**
     * Validate fields when updated.
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'endDate') {
            $this->validateOnly('startDate');
        }
        if ($propertyName === 'startDate') {
            $this->validateOnly('endDate');
        }
    }

    /**
     * Reset date filters.
     */
    public function resetFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->validate();
    }

    /**
     * Load report names from configuration.
     */
    public function loadReportsNames()
    {
        if (config('reports.reports')) {
            $this->reports = config('reports.reports');
        }
    }
}
