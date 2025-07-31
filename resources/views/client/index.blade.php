@extends('common.main')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title">View Existing Clients</h4>
                            <p class="card-description">
                                List of all clients in the system
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="columnSettings" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-cog"></i> Column Settings
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="columnSettings">
                                    <li><h6 class="dropdown-header">Toggle Columns</h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="reference" checked> Reference
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="name" checked> Name
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="email" checked> Email
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="mobile" checked> Mobile
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="lead_source" checked> Lead Source
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="created" checked> Created
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="column-toggle" data-column="actions" checked> Actions
                                        </label>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item" id="showAllColumns">
                                            <i class="mdi mdi-eye"></i> Show All
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" id="hideAllColumns">
                                            <i class="mdi mdi-eye-off"></i> Hide All
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Add New Client
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="clientsTable">
                                <thead>
                                    <tr>
                                        <th class="column-reference">Reference</th>
                                        <th class="column-name">Name</th>
                                        <th class="column-email">Email</th>
                                        <th class="column-mobile">Mobile</th>
                                        <th class="column-lead_source">Lead Source</th>
                                        <th class="column-created">Created</th>
                                        <th class="column-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td class="column-reference">E0{{ $client->id ?? 'N/A' }}</td>
                                            <td class="column-name">{{ $client->full_name }}</td>
                                            <td class="column-email">{{ $client->email_address }}</td>
                                            <td class="column-mobile">{{ $client->mobile_number }}</td>
                                            <td class="column-lead_source">
                                                @if($client->lead_source)
                                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $client->lead_source)) }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="column-created">{{ $client->created_at->format('d/m/Y') }}</td>
                                            <td class="column-actions">
                                                <div class="btn-group" role="group">
                                                    
                                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $clients->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-account-multiple-outline" style="font-size: 4rem; color: #6c757d;"></i>
                            <h5 class="mt-3 text-muted">No clients found</h5>
                            <p class="text-muted">Start by adding your first client.</p>
                            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Add New Client
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.badge {
    font-size: 0.75rem;
}

.dropdown-item label {
    cursor: pointer;
    margin-bottom: 0;
    width: 100%;
}

.dropdown-item input[type="checkbox"] {
    margin-right: 8px;
}

.column-hidden {
    display: none !important;
}

.dropdown-menu {
    min-width: 200px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load saved column preferences from localStorage
    loadColumnPreferences();
    
    // Add event listeners for column toggles
    document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            toggleColumn(this.dataset.column, this.checked);
            saveColumnPreferences();
        });
    });
    
    // Show all columns
    document.getElementById('showAllColumns').addEventListener('click', function() {
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.checked = true;
            toggleColumn(checkbox.dataset.column, true);
        });
        saveColumnPreferences();
    });
    
    // Hide all columns
    document.getElementById('hideAllColumns').addEventListener('click', function() {
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            checkbox.checked = false;
            toggleColumn(checkbox.dataset.column, false);
        });
        saveColumnPreferences();
    });
    
    function toggleColumn(columnName, show) {
        const elements = document.querySelectorAll(`.column-${columnName}`);
        elements.forEach(function(element) {
            if (show) {
                element.classList.remove('column-hidden');
            } else {
                element.classList.add('column-hidden');
            }
        });
    }
    
    function saveColumnPreferences() {
        const preferences = {};
        document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
            preferences[checkbox.dataset.column] = checkbox.checked;
        });
        localStorage.setItem('clientTableColumns', JSON.stringify(preferences));
    }
    
    function loadColumnPreferences() {
        const saved = localStorage.getItem('clientTableColumns');
        if (saved) {
            const preferences = JSON.parse(saved);
            document.querySelectorAll('.column-toggle').forEach(function(checkbox) {
                const columnName = checkbox.dataset.column;
                if (preferences.hasOwnProperty(columnName)) {
                    checkbox.checked = preferences[columnName];
                    toggleColumn(columnName, preferences[columnName]);
                }
            });
        }
    }
});
</script>
@endsection 