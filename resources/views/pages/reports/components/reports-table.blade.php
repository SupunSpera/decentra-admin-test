<div class="col-xl-12 col-lg-12">
    <!-- Date Filter Inputs -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" wire:model="startDate" class="form-control">
            @error('startDate')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" wire:model="endDate" class="form-control">
            @error('endDate')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-4 pt-2">
            <button class="btn btn-secondary mt-4" wire:click="resetFilters">Reset</button>
        </div>
    </div>

    <!-- Reports Table -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Report</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($reports))
                @foreach ($reports as $report)
                    <tr>
                        <td scope="row">{{ $report['name'] }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm {{ $errors->isNotEmpty() ? 'disabled' : '' }}"
                                href="{{ route('reports.view', ['reportId' => $report['id'], 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                target="_blank" {{ $errors->isNotEmpty() ? 'aria-disabled=true' : '' }}>
                                <i class="far fa-eye"></i>
                            </a>

                            <a class="btn btn-success btn-sm {{ $errors->isNotEmpty() ? 'disabled' : '' }}"
                                href="{{ route('reports.export', ['reportId' => $report['id'], 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                target="_blank" {{ $errors->isNotEmpty() ? 'aria-disabled=true' : '' }}>
                                Export
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-center">No reports available.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
