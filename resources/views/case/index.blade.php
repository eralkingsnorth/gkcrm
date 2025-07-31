@extends('common.main')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title">View All Cases</h4>
                            <p class="card-description">List of all cases in the system</p>
                        </div>
                        <a href="{{ route('cases.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Create New Case
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center py-5">
                        <h5 class="text-muted">No cases found</h5>
                        <p class="text-muted">Start by creating your first case.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 