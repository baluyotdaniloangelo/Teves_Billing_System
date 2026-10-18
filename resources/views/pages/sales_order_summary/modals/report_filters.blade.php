<div class="modal fade" id="CreateReportModal" tabindex="-1" aria-labelledby="summaryFilterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="summaryFilterTitle">Sales Order Report Filters</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="generate_report_form">
            <div class="modal-body p-4">
                <div id="summary_filter_error" class="alert alert-danger d-none" role="alert"></div>
                <div class="row g-3">
                    <div class="col-md-6"><label for="company_header" class="form-label">Branch</label><select class="form-select" name="company_header" id="company_header" required>
                        <option value="All">All Branches</option>
                        @foreach($teves_branch as $branch)<option value="{{ $branch->branch_id }}">{{ $branch->branch_code }} — {{ $branch->branch_name }}</option>@endforeach
                    </select><div class="form-text">Includes branches available to your account.</div></div>
                    <div class="col-md-6"><label for="client_id" class="form-label">Client</label><input class="form-control" list="client_name" name="client_name" id="client_id" value="All" autocomplete="off" required><datalist id="client_name">
                        <option value="All" label="All Clients" data-id="All"></option>
                        @foreach($client_data as $client)<option value="{{ $client->client_name }}" data-id="{{ $client->client_id }}"></option>@endforeach
                    </datalist><div class="form-text">Choose a client or enter All.</div></div>
                    <div class="col-md-6"><label for="start_date" class="form-label">Start Date</label><input type="date" class="form-control" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" required></div>
                    <div class="col-md-6"><label for="end_date" class="form-label">End Date</label><input type="date" class="form-control" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" required></div>
                </div>
            </div>
            <div class="modal-footer"><span id="loading_data" class="small me-auto d-none" role="status"><span class="spinner-border spinner-border-sm me-1"></span>Generating report…</span><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success" id="generate_report"><i class="bi bi-bar-chart me-1"></i>Generate Report</button></div>
        </form>
    </div></div>
</div>
