@extends('common.main')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Case</h4>
                    <p class="card-description">Update case information</p>
                    
                    <form method="POST" action="{{ route('cases.update', 1) }}" id="caseForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Case Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="case_number">Case Number</label>
                                    <input type="text" class="form-control" id="case_number" name="case_number" value="{{ old('case_number') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="case_title">Case Title</label>
                                    <input type="text" class="form-control" id="case_title" name="case_title" value="{{ old('case_title') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="case_type">Case Type</label>
                                    <select class="form-control" id="case_type" name="case_type" required>
                                        <option value="">Please select a case type</option>
                                        <option value="civil" {{ old('case_type') == 'civil' ? 'selected' : '' }}>Civil</option>
                                        <option value="criminal" {{ old('case_type') == 'criminal' ? 'selected' : '' }}>Criminal</option>
                                        <option value="family" {{ old('case_type') == 'family' ? 'selected' : '' }}>Family</option>
                                        <option value="commercial" {{ old('case_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select class="form-control" id="priority" name="priority" required>
                                        <option value="">Please select priority</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                                        <i class="mdi mdi-content-save"></i> Update Case
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
@endsection 