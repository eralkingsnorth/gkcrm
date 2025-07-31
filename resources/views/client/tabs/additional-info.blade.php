<!-- Additional Info Tab -->
<div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
    <form method="POST" action="{{ $client ? route('clients.update', $client->id) : route('clients.store') }}" id="additionalInfoForm">
        @csrf
        @if($client)
            @method('PUT')
        @endif
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="client_status">Client Status</label>
                    <select class="form-control" id="client_status" name="client_status">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('client_status', $client->client_status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('client_status', $client->client_status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('client_status', $client->client_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ old('client_status', $client->client_status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="client_type">Client Type</label>
                    <select class="form-control" id="client_type" name="client_type">
                        <option value="">Select Type</option>
                        <option value="individual" {{ old('client_type', $client->client_type ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="corporate" {{ old('client_type', $client->client_type ?? '') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                        <option value="partnership" {{ old('client_type', $client->client_type ?? '') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="source">Lead Source</label>
                    <select class="form-control" id="source" name="source">
                        <option value="">Select Source</option>
                        @foreach($leadSources ?? [] as $leadSource)
                            <option value="{{ $leadSource->name }}" {{ old('source', $client->lead_source ?? '') == $leadSource->name ? 'selected' : '' }}>
                                {{ $leadSource->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="assigned_to">Assigned To</label>
                    <select class="form-control" id="assigned_to" name="assigned_to">
                        <option value="">Select User</option>
                        <option value="1" {{ old('assigned_to', $client->assigned_to ?? '') == '1' ? 'selected' : '' }}>John Doe</option>
                        <option value="2" {{ old('assigned_to', $client->assigned_to ?? '') == '2' ? 'selected' : '' }}>Jane Smith</option>
                        <option value="3" {{ old('assigned_to', $client->assigned_to ?? '') == '3' ? 'selected' : '' }}>Mike Johnson</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Notes Section -->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="notes">Initial Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Add initial notes about this client...">{{ old('notes', $client->notes ?? '') }}</textarea>
                    <small class="form-text text-muted">This note will be saved with the client record.</small>
                </div>
            </div>
        </div>
        
        <!-- Additional Notes Section (only for existing clients) -->
        @if($client)
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>Additional Notes</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#noteModal">
                            <i class="mdi mdi-note-plus"></i> Add Note
                        </button>
                    </div>
                    <div id="notesList" class="mt-3">
                        <!-- Notes will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter tags separated by commas" value="{{ old('tags', $client->tags ?? '') }}">
                    <small class="form-text text-muted">Example: VIP, Premium, New</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="priority">Priority Level</label>
                    <select class="form-control" id="priority" name="priority">
                        <option value="low" {{ old('priority', $client->priority ?? 'medium') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $client->priority ?? 'medium') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                        <option value="high" {{ old('priority', $client->priority ?? 'medium') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $client->priority ?? 'medium') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> {{ $client ? 'Update Additional Info' : 'Save Additional Info' }}
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Clients
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Note Modal (only for existing clients) -->
@if($client)
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Note Content</label>
                        <textarea class="form-control" id="noteContent" name="noteContent" rows="4" placeholder="Enter your note..."></textarea> 
                    </div>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const clientId = {{ $client->id }};
    
    // Load existing notes
    loadClientNotes(clientId);
    
    // Handle note form submission
    $('#noteForm').on('submit', function(e) {
        e.preventDefault();
        
        const noteContent = $('#noteContent').val().trim();
        if (noteContent) {
            $.ajax({
                url: `/clients/${clientId}/notes`,
                type: 'POST',
                data: {
                    content: noteContent,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#noteContent').val('');
                        $('#noteModal').modal('hide');
                        loadClientNotes(clientId);
                        showAlert('Note added successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Failed to add note. Please try again.';
                    showAlert(error, 'error');
                }
            });
        }
    });
});

function loadClientNotes(clientId) {
    $.get(`/clients/${clientId}/notes`, function(response) {
        if (response.success) {
            displayNotes(response.notes);
        }
    });
}

function displayNotes(notes) {
    const notesList = $('#notesList');
    notesList.empty();
    
    if (notes.length === 0) {
        notesList.html('<p class="text-muted small">No additional notes added yet.</p>');
        return;
    }
    
    notes.forEach(function(note) {
        const noteHtml = `
            <div class="card mb-2" data-note-id="${note.id}">
                <div class="card-body p-2">
                    <p class="card-text small mb-1">${note.content}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${note.formatted_date} by ${note.creator ? note.creator.name : 'Unknown'}</small>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteNote(${note.id})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        notesList.append(noteHtml);
    });
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note?')) {
        $.ajax({
            url: `/notes/${noteId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`[data-note-id="${noteId}"]`).remove();
                    showAlert('Note deleted successfully!', 'success');
                }
            },
            error: function() {
                showAlert('Failed to delete note. Please try again.', 'error');
            }
        });
    }
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the additional tab
    $('#additional').prepend(alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endif 