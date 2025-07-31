@extends('common.main')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="mdi mdi-account-plus"></i> 
                            {{ $client ? 'Edit Client' : 'Create New Client' }}
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                                    <i class="mdi mdi-account-details"></i> Client Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ !$client ? 'disabled' : '' }}" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false" {{ !$client ? 'disabled' : '' }}>
                                    <i class="mdi mdi-file-document"></i> Document Uploads
                                    @if(!$client)
                                        <span class="badge bg-warning ms-1">Save First</span>
                                    @endif
                                </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link {{ !$client ? 'disabled' : '' }}" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab" aria-controls="additional" aria-selected="false" {{ !$client ? 'disabled' : '' }}>
                                    <i class="mdi mdi-information"></i> Additional Info
                                    @if(!$client)
                                        <span class="badge bg-warning ms-1">Save First</span>
                                    @endif
                                </button>
                            </li> --}}
                           
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="clientTabsContent">
                            @include('client.tabs.client-details')
                            @include('client.tabs.document-uploads')
                            @include('client.tabs.additional-info')
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.75rem 1rem;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #007bff;
}

.nav-tabs .nav-link i {
    margin-right: 0.5rem;
}

.nav-tabs .nav-link.disabled {
    color: #6c757d;
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.nav-tabs .nav-link.disabled:hover {
    border-color: transparent;
    color: #6c757d;
}

.badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn i {
    margin-right: 0.5rem;
}
</style>

<script>
$(document).ready(function() {
    // Check if this is a new client creation
    const isNewClient = {{ $client ? 'false' : 'true' }};
    
    if (isNewClient) {
        // Disable document upload and additional info tabs for new clients
        $('#documents-tab, #additional-tab').addClass('disabled').prop('disabled', true);
        
        // Handle form submission for new clients
        $('#clientForm').on('submit', function(e) {
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="mdi mdi-loading mdi-spin"></i> Creating...').prop('disabled', true);
            
            // Submit form normally - the page will redirect after successful creation
        });
    } else {
        // For existing clients, enable all tabs
        $('#documents-tab, #additional-tab').removeClass('disabled').prop('disabled', false);
        
        // Remove the "Save First" badges
        $('.badge.bg-warning').remove();
    }
    
    // Prevent clicking on disabled tabs
    $('.nav-link.disabled').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
});
</script>
@endsection 