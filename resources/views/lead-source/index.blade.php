@extends('common.main')

@section('content')
<div class="content-wrapper">
    <!-- Form Section -->
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add New Lead Source</h4>
                    <p class="card-description">Create a new lead source</p>
                    
                    <form method="POST" action="{{ route('lead-sources.store') }}" id="leadSourceForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Lead Source Name*</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add Lead Source
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lead Sources</h4>
                    <p class="card-description">Manage all lead sources</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leadSources ?? [] as $leadSource)
                                    <tr>
                                        <td>{{ $leadSource->id }}</td>
                                        <td>{{ $leadSource->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary edit-lead-source" 
                                                    data-id="{{ $leadSource->id }}"
                                                    data-name="{{ $leadSource->name }}">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-lead-source" 
                                                    data-id="{{ $leadSource->id }}"
                                                    data-name="{{ $leadSource->name }}">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No lead sources found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editLeadSourceModal" tabindex="-1" aria-labelledby="editLeadSourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeadSourceModalLabel">Edit Lead Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLeadSourceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Lead Source Name*</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Lead Source</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteLeadSourceModal" tabindex="-1" aria-labelledby="deleteLeadSourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLeadSourceModalLabel">Delete Lead Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the lead source "<span id="deleteLeadSourceName"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteLeadSourceForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Edit Lead Source
    $('.edit-lead-source').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#edit_name').val(name);
        $('#editLeadSourceForm').attr('action', '{{ route("lead-sources.index") }}/' + id);
        $('#editLeadSourceModal').modal('show');
    });

    // Delete Lead Source
    $('.delete-lead-source').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#deleteLeadSourceName').text(name);
        $('#deleteLeadSourceForm').attr('action', '{{ route("lead-sources.index") }}/' + id);
        $('#deleteLeadSourceModal').modal('show');
    });
});
</script>
@endpush
@endsection 