@extends('common.main')

@section('content')
<div class="content-wrapper">
    <!-- Form Section -->
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add New Creditor</h4>
                    <p class="card-description">Create a new creditor</p>
                    
                    <form method="POST" action="{{ route('creditors.store') }}" id="creditorForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Creditor Name*</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Enter creditor name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Creditor Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter creditor email">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="creditor_type">Creditor Type</label>
                                    <select class="form-control" id="creditor_type" name="creditor_type">
                                        <option value="">Select Creditor Type</option>
                                        <option value="bank" {{ old('creditor_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="credit_card" {{ old('creditor_type') == 'credit_card' ? 'selected' : '' }}>Credit Card Company</option>
                                        <option value="utility" {{ old('creditor_type') == 'utility' ? 'selected' : '' }}>Utility Company</option>
                                        <option value="loan" {{ old('creditor_type') == 'loan' ? 'selected' : '' }}>Loan Company</option>
                                        <option value="mortgage" {{ old('creditor_type') == 'mortgage' ? 'selected' : '' }}>Mortgage Company</option>
                                        <option value="debt_collection" {{ old('creditor_type') == 'debt_collection' ? 'selected' : '' }}>Debt Collection Agency</option>
                                        <option value="other" {{ old('creditor_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address_line_1">Address Line 1</label>
                                    <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" placeholder="Enter address line 1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address_line_2">Address Line 2</label>
                                    <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}" placeholder="Enter address line 2 (optional)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="town_city">Town/City</label>
                                    <input type="text" class="form-control" id="town_city" name="town_city" value="{{ old('town_city') }}" placeholder="Enter town or city">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="county">County</label>
                                    <input type="text" class="form-control" id="county" name="county" value="{{ old('county') }}" placeholder="Enter county">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'United Kingdom') }}" placeholder="Enter country">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="postcode">Postcode</label>
                                    <input type="text" class="form-control" id="postcode" name="postcode" value="{{ old('postcode') }}" placeholder="Enter postcode">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Creditor
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add Creditor
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="mdi mdi-refresh"></i> Reset
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
                    <h4 class="card-title">Creditors</h4>
                    <p class="card-description">Manage all creditors</p>

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
                                    <th>Email</th>
                                    <th>Type</th>

                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($creditors ?? [] as $creditor)
                                    <tr>
                                        <td>{{ $creditor->id }}</td>
                                        <td>{{ $creditor->name }}</td>
                                        <td>{{ $creditor->email ?? 'N/A' }}</td>
                                        <td>
                                            @if($creditor->creditor_type)
                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $creditor->creditor_type)) }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($creditor->address_line_1)
                                                {{ $creditor->address_line_1 }}<br>
                                                @if($creditor->address_line_2){{ $creditor->address_line_2 }}<br>@endif
                                                @if($creditor->town_city){{ $creditor->town_city }}, @endif
                                                @if($creditor->county){{ $creditor->county }}<br>@endif
                                                @if($creditor->postcode){{ $creditor->postcode }}@endif
                                            @else
                                                <span class="text-muted">No address</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($creditor->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary edit-creditor" 
                                                        data-id="{{ $creditor->id }}"
                                                        data-name="{{ $creditor->name }}"
                                                        data-email="{{ $creditor->email }}"
                                                        data-creditor-type="{{ $creditor->creditor_type }}"

                                                        data-address-line-1="{{ $creditor->address_line_1 }}"
                                                        data-address-line-2="{{ $creditor->address_line_2 }}"
                                                        data-town-city="{{ $creditor->town_city }}"
                                                        data-county="{{ $creditor->county }}"
                                                        data-country="{{ $creditor->country }}"
                                                        data-postcode="{{ $creditor->postcode }}"
                                                        data-is-active="{{ $creditor->is_active }}"
                                                        title="Edit Creditor">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-creditor" 
                                                        data-id="{{ $creditor->id }}"
                                                        data-name="{{ $creditor->name }}"
                                                        title="Delete Creditor">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No creditors found</td>
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
<div class="modal fade" id="editCreditorModal" tabindex="-1" aria-labelledby="editCreditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCreditorModalLabel">Edit Creditor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCreditorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Creditor Name*</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email">Creditor Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_creditor_type">Creditor Type</label>
                                <select class="form-control" id="edit_creditor_type" name="creditor_type">
                                    <option value="">Select Creditor Type</option>
                                    <option value="bank">Bank</option>
                                    <option value="credit_card">Credit Card Company</option>
                                    <option value="utility">Utility Company</option>
                                    <option value="loan">Loan Company</option>
                                    <option value="mortgage">Mortgage Company</option>
                                    <option value="debt_collection">Debt Collection Agency</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_address_line_1">Address Line 1</label>
                                <input type="text" class="form-control" id="edit_address_line_1" name="address_line_1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_address_line_2">Address Line 2</label>
                                <input type="text" class="form-control" id="edit_address_line_2" name="address_line_2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_town_city">Town/City</label>
                                <input type="text" class="form-control" id="edit_town_city" name="town_city">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_county">County</label>
                                <input type="text" class="form-control" id="edit_county" name="county">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_country">Country</label>
                                <input type="text" class="form-control" id="edit_country" name="country">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_postcode">Postcode</label>
                                <input type="text" class="form-control" id="edit_postcode" name="postcode">
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                        <label class="form-check-label" for="edit_is_active">
                            Active Creditor
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Creditor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCreditorModal" tabindex="-1" aria-labelledby="deleteCreditorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCreditorModalLabel">Delete Creditor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the creditor "<span id="deleteCreditorName"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCreditorForm" method="POST" style="display: inline;">
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
    // Edit Creditor
    $('.edit-creditor').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const creditorType = $(this).data('creditor-type');

        const addressLine1 = $(this).data('address-line-1');
        const addressLine2 = $(this).data('address-line-2');
        const townCity = $(this).data('town-city');
        const county = $(this).data('county');
        const country = $(this).data('country');
        const postcode = $(this).data('postcode');
        const isActive = $(this).data('is-active');

        // Populate the edit modal
        $('#edit_name').val(name);
        $('#edit_email').val(email || '');
        $('#edit_creditor_type').val(creditorType || '');

        $('#edit_address_line_1').val(addressLine1 || '');
        $('#edit_address_line_2').val(addressLine2 || '');
        $('#edit_town_city').val(townCity || '');
        $('#edit_county').val(county || '');
        $('#edit_country').val(country || 'United Kingdom');
        $('#edit_postcode').val(postcode || '');
        $('#edit_is_active').prop('checked', isActive == 1);
        
        // Set the form action
        $('#editCreditorForm').attr('action', '{{ route("creditors.index") }}/' + id);
        
        // Show the modal
        $('#editCreditorModal').modal('show');
    });

    // Delete Creditor
    $('.delete-creditor').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        // Populate the delete modal
        $('#deleteCreditorName').text(name);
        $('#deleteCreditorForm').attr('action', '{{ route("creditors.index") }}/' + id);
        
        // Show the modal
        $('#deleteCreditorModal').modal('show');
    });

    // Form validation for edit form
    $('#editCreditorForm').submit(function(e) {
        const name = $('#edit_name').val().trim();
        if (!name) {
            e.preventDefault();
            alert('Creditor name is required!');
            $('#edit_name').focus();
            return false;
        }
    });

    // Form validation for create form
    $('#creditorForm').submit(function(e) {
        const name = $('#name').val().trim();
        if (!name) {
            e.preventDefault();
            alert('Creditor name is required!');
            $('#name').focus();
            return false;
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Close modal on escape key
    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            $('.modal').modal('hide');
        }
    });
});
</script>
@endpush
@endsection