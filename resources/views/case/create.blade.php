@extends('common.main')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create A New Case</h4>
                    <p class="card-description">Add a new case to the system</p>
                    
                    <form method="POST" action="{{ route('cases.store') }}" id="caseForm">
                        @csrf
                        
                        <!-- Client Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id">Select Client:*</label>
                                    <select class="form-control select2" id="client_id" name="client_id" required>
                                        <option value="">Search for a client using their forename, surname, email, mobile or ID (G0xxx)</option>
                                        @if(old('client_id') && old('client_name'))
                                            <option value="{{ old('client_id') }}" selected>{{ old('client_name') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="case_reference">Case Reference</label>
                                    <input type="text" class="form-control" id="case_reference" name="case_reference" value="{{ old('case_reference') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Creditor Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="creditor_id">Creditor:*</label>
                                    <select class="form-control select2" id="creditor_id" name="creditor_id" required>
                                        <option value="">Search for a creditor by name, email, or type</option>
                                        @if(old('creditor_id') && old('creditor_name'))
                                            <option value="{{ old('creditor_id') }}" selected>{{ old('creditor_name') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="creditor_name" name="creditor_name" value="{{ old('creditor_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">Agreement Number:</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount:</label>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date:</label>
                                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') : '' }}" placeholder="Select start date" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="other_data">Other Data:</label>
                                    <textarea class="form-control" id="other_data" name="other_data" rows="3">{{ old('other_data') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 
                            </div>
                        </div>

                        <!-- Email Status and Banking Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_status">Email Status:*</label>
                                    <select class="form-control" id="email_status" name="email_status" required>
                                        <option value="">Please Select</option>
                                        <option value="pending" {{ old('email_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="sent" {{ old('email_status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                        <option value="delivered" {{ old('email_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="failed" {{ old('email_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Account Reference and Case Category -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_reference">Lander Reference:</label>
                                    <input type="text" class="form-control" id="account_reference" name="account_reference" value="{{ old('account_reference') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_id">Case Status:</label>
                                    <select class="form-control" id="status_id" name="status_id">
                                        <option value="">Please select a status</option>
                                        @foreach($caseStatuses ?? [] as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Case Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('cases.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-plus"></i> Create Case
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for client selection
    $('#client_id').select2({
        placeholder: 'Search for a client using their forename, surname, email, mobile or ID (G0xxx)',
        allowClear: true,
        minimumInputLength: 2, // Start searching after 2 characters
        ajax: {
            url: '{{ route("api.clients.search") }}',
            dataType: 'json',
            delay: 300, // Increased delay to reduce API calls
            data: function(params) {
                return {
                    search: params.term,
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: data.next_page_url != null
                    }
                };
            },
            cache: true
        },
        templateResult: formatClient,
        templateSelection: formatClientSelection
    });

    function formatClient(client) {
        if (client.loading) return client.text;
        return $(`
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${client.forename} ${client.surname}</strong><br>
                    <small>ID: G0${client.id}</small>
                </div>
                <div class="text-muted">
                    <small>${client.email_address || 'N/A'}</small><br>
                    <small>${client.mobile_number || 'N/A'}</small>
                </div>
            </div>
        `);
    }

    function formatClientSelection(client) {
        if (!client.id) return client.text;
        return client.forename + ' ' + client.surname + ' (G0' + client.id + ')';
    }

    // Initialize Select2 for creditor selection
    $('#creditor_id').select2({
        placeholder: 'Search for a creditor by name, email, or type',
        allowClear: true,
        minimumInputLength: 2, // Start searching after 2 characters
        ajax: {
            url: '{{ route("api.creditors.search") }}',
            dataType: 'json',
            delay: 300, // Increased delay to reduce API calls
            data: function(params) {
                return {
                    search: params.term,
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: data.next_page_url != null
                    }
                };
            },
            cache: true
        },
        templateResult: formatCreditor,
        templateSelection: formatCreditorSelection
    });

    function formatCreditor(creditor) {
        if (creditor.loading) return creditor.text;
        return $(`
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${creditor.name}</strong><br>
                    <small>Type: ${creditor.creditor_type || 'N/A'}</small>
                </div>
                <div class="text-muted">
                    <small>${creditor.email || 'N/A'}</small>
                </div>
            </div>
        `);
    }

    function formatCreditorSelection(creditor) {
        if (!creditor.id) return creditor.text;
        return creditor.name;
    }

    // Update hidden creditor_name field when creditor is selected
    $('#creditor_id').on('select2:select', function(e) {
        var data = e.params.data;
        $('#creditor_name').val(data.name);
    });

    // Clear hidden creditor_name field when creditor is cleared
    $('#creditor_id').on('select2:clear', function(e) {
        $('#creditor_name').val('');
    });

    // Initialize date picker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        clearBtn: true,
        orientation: 'bottom auto',
        startDate: '-100y',
        endDate: '+10y'
    });

    // Set today's date as default if no value is set
    if (!$('#start_date').val()) {
        $('#start_date').datepicker('setDate', new Date());
    }

    // Convert UK format to database format before form submission
    $('#caseForm').on('submit', function(e) {
        var startDate = $('#start_date').val();
        if (startDate) {
            // Convert dd/mm/yyyy to yyyy-mm-dd for database
            var parts = startDate.split('/');
            if (parts.length === 3) {
                var ukDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                $('#start_date').val(ukDate);
            }
        }
    });
});
</script>
@endpush
@endsection 