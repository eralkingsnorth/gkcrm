@extends('common.main')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title">Case Details</h4>
                            <p class="card-description">View case information</p>
                        </div>
                        <div>
                            <a href="{{ route('cases.edit', 1) }}" class="btn btn-primary">
                                <i class="mdi mdi-pencil"></i> Edit Case
                            </a>
                            <a href="{{ route('cases.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Case Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Case Number:</strong> <span class="text-muted">N/A</span></p>
                                            <p><strong>Case Title:</strong> <span class="text-muted">N/A</span></p>
                                            <p><strong>Case Type:</strong> <span class="text-muted">N/A</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Priority:</strong> <span class="text-muted">N/A</span></p>
                                            <p><strong>Status:</strong> <span class="text-muted">N/A</span></p>
                                            <p><strong>Created:</strong> <span class="text-muted">N/A</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Description:</strong></p>
                                            <p class="text-muted">No description available.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Case Info</h5>
                                    <p><strong>Created:</strong> <span class="text-muted">N/A</span></p>
                                    <p><strong>Updated:</strong> <span class="text-muted">N/A</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 