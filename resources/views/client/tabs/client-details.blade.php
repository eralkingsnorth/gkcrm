 <!-- Client Details Tab -->
 <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
    
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
         
         <div class="row">
            <div class="col-8"> 
                <form method="POST" action="{{ $client ? route('clients.update', $client->id) : route('clients.store') }}" id="clientDetailsForm" data-client-id="{{ $client->id ?? '' }}">
                    @csrf
                    @if($client)
                        @method('PUT')
                    @endif
                <!-- Reference and Lead Source -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference">Reference</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="reference" name="reference" 
                                       value="{{ old('reference', $client->reference ?? '') }}" readonly>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lead_source">Lead Source</label>
                            <select class="form-control" id="lead_source" name="lead_source">
                                <option value="">Please select a source</option>
                                @foreach($leadSources ?? [] as $leadSource)
                                    <option value="{{ $leadSource->name }}" {{ old('lead_source', $client->lead_source ?? '') == $leadSource->name ? 'selected' : '' }}>
                                        {{ $leadSource->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information Section -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="section-title mt-4 mb-3">Personal Information</h6>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <select class="form-control" id="title" name="title">
                                <option value="">Please select a title</option>
                                <option value="Mr" {{ old('title', $client->title ?? '') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                <option value="Mrs" {{ old('title', $client->title ?? '') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                <option value="Miss" {{ old('title', $client->title ?? '') == 'Miss' ? 'selected' : '' }}>Miss</option>
                                <option value="Ms" {{ old('title', $client->title ?? '') == 'Ms' ? 'selected' : '' }}>Ms</option>
                                <option value="Dr" {{ old('title', $client->title ?? '') == 'Dr' ? 'selected' : '' }}>Dr</option>
                                <option value="Prof" {{ old('title', $client->title ?? '') == 'Prof' ? 'selected' : '' }}>Prof</option>
                                <option value="Sir" {{ old('title', $client->title ?? '') == 'Sir' ? 'selected' : '' }}>Sir</option>
                                <option value="Lady" {{ old('title', $client->title ?? '') == 'Lady' ? 'selected' : '' }}>Lady</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="forename">Forename *</label>
                            <input type="text" class="form-control" id="forename" name="forename" value="{{ old('forename', $client->forename ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="surname">Surname *</label>
                            <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname', $client->surname ?? '') }}" required>
                        </div>
                    </div>  
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth_display">Date Of Birth</label>
                            <input type="text" class="form-control datepicker" id="date_of_birth_display" 
                                   placeholder="DD/MM/YYYY" 
                                   value="{{ old('date_of_birth', $client && $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->format('d/m/Y') : '') }}" 
                                   autocomplete="off">
                            <input type="hidden" id="date_of_birth" name="date_of_birth" 
                                   value="{{ old('date_of_birth', $client->date_of_birth ?? '') }}">
                        </div>
                    </div>
                                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="country_of_birth">Country Of Birth</label>
                             <select class="form-control select2" id="country_of_birth" name="country_of_birth">
                                 <option value="">Please select a country</option>
                             </select>
                         </div>
                     </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marital_status">Marital Status</label>
                            <select class="form-control" id="marital_status" name="marital_status">
                                <option value="">Please select a status</option>
                                <option value="single" {{ old('marital_status', $client->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $client->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status', $client->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status', $client->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="civil_partnership" {{ old('marital_status', $client->marital_status ?? '') == 'civil_partnership' ? 'selected' : '' }}>Civil Partnership</option>
                                <option value="separated" {{ old('marital_status', $client->marital_status ?? '') == 'separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Contact Information Section -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="section-title mt-4 mb-3">Contact Information</h6>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_address">Email Address *</label>
                            <input type="email" class="form-control" id="email_address" name="email_address" value="{{ old('email_address', $client->email_address ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number *</label>
                            <input type="tel" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $client->mobile_number ?? '') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="home_phone">Home Phone</label>
                            <input type="tel" class="form-control" id="home_phone" name="home_phone" value="{{ old('home_phone', $client->home_phone ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Empty column for balance -->
                    </div>
                </div>
                
              
                
                <!-- Address Information Section -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="section-title mt-4 mb-3">Address Information</h6>
                    </div>
                </div>
                
                 <div class="row">
                    <div class="col-md-5">
                        <div class="form-group postcode-final">
                            <label for="postcode">Find your Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter your postcode" value="{{ old('postcode', $client->postcode ?? '') }}">
                                <button type="button" class="btn btn-primary" id="searchPostcode">
                                    <i class="mdi mdi-magnify"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address">Select Address</label>
                            <select class="form-control" id="address" name="address">
                                <option value="">-- Search for an address first --</option>
                            </select>
                        </div>
                    </div>
                     <div class="col-md-3">
                         <div class="form-group">
                             <label for="house_number">House Number</label>
                             <input type="text" class="form-control" id="house_number" name="house_number" placeholder="e.g. 123" value="{{ old('house_number', $client->house_number ?? '') }}">
                         </div>
                     </div>
                   
                     
                 </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address_line_1">Address Line 1 *</label>
                            <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $client->address_line_1 ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address_line_2">Address Line 2</label>
                            <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $client->address_line_2 ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address_line_2">Address Line 3</label>
                            <input type="text" class="form-control" id="address_line_3" name="address_line_3" value="{{ old('address_line_3', $client->address_line_3 ?? '') }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="town_city">Town/City</label>
                            <input type="text" class="form-control" id="town_city" name="town_city" value="{{ old('town_city', $client->town_city ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" class="form-control" id="county" name="county" value="{{ old('county', $client->county ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="postcode_final">Postcode *</label>
                            <input type="text" class="form-control" id="postcode_final" name="postcode_final" value="{{ old('postcode_final', $client->postcode_final ?? '') }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="country">Country</label>
                             <select class="form-control select2" id="country" name="country">
                                 <option value="">Please select a country</option>
                             </select>
                         </div>
                     </div>
                    <div class="col-md-6">
                        <!-- Empty column for balance -->
                    </div>
                </div>
                
                <!-- Additional Information Section -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="section-title mt-4 mb-3">Additional Information</h6>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="other">Other</label>
                            <textarea class="form-control textarea-height" id="other" name="other" rows="8">{{ old('other', $client->other ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> {{ $client ? 'Update Client' : 'Create Client' }}
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Clients
                        </a>
                        
                        @if($client)
                        <!-- Resend Notification Buttons - Only visible when editing existing client -->
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">Resend Notifications</h6>
                            <div class="d-flex gap-2">
                                                                                                  <button type="button" class="btn btn-outline-info btn-sm" onclick="resendEmail({{ $client->id }})" id="resendEmailBtn">
                                     <i class="mdi mdi-email"></i> <span id="resendEmailText">Resend Email</span>
                                 </button>
                                 <button type="button" class="btn btn-outline-success btn-sm" onclick="resendSms({{ $client->id }})" id="resendSmsBtn">
                                     <i class="mdi mdi-message-text"></i> <span id="resendSmsText">Resend SMS</span>
                                 </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </form>
            
            
            </div>
            <div class="col-4"> 
                @if($client)
                <!-- Notes Section - Only visible when editing existing client -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">Client Notes</h6>
                                 <div>
                                     <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                         <i class="mdi mdi-plus"></i> Add Note
                                     </button>
                                 </div>
                             </div>
                            <div class="card-body">

                             <div id="notes-container">
                                      @if($client && $client->clientNotes && $client->clientNotes->count() > 0)
                                                                                   @foreach($client->clientNotes as $note)
                                             <div class="note-item" data-note-id="{{ $note->id }}">
                                                 <div class="note-header">
                                                     <div class="note-meta">
                                                         <span class="note-author">{{ $note->creator ? $note->creator->name : 'Unknown User' }}</span>
                                                         <span class="note-date">{{ $note->created_at->format('M d, Y H:i') }}</span>
                                                     </div>
                                                     <div class="note-actions">
                                                         <form method="POST" action="{{ route('client.notes.delete', $note->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this note?')">
                                                             @csrf
                                                             @method('DELETE')
                                                             <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                 <i class="mdi mdi-delete"></i> 
                                                             </button>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="note-content">{{ $note->content }}</div>
                                             </div>
                                         @endforeach
                                     @else
                                         <div class="text-center text-muted py-5">
                                             <i class="mdi mdi-note-text-outline" style="font-size: 4rem; color: #dee2e6;"></i>
                                             <p class="mt-3 mb-0" style="font-size: 1.1rem; color: #6c757d;">No notes yet</p>
                                             <p class="text-muted" style="font-size: 0.9rem;">Click "Add Note" to create your first note</p>
                                         </div>
                                     @endif
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        @if($client)
        <!-- Add Note Modal -->
        <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                                         <div class="modal-body">
                         <form method="POST" action="{{ route('client.notes.store', $client->id ?? '') }}">
                             @csrf
                             <div class="form-group">
                                 <label for="noteContent">Note Content *</label>
                                 <textarea class="form-control" id="noteContent" name="content" rows="4" maxlength="1000" placeholder="Enter your note here..." required></textarea>
                                 <small class="text-muted">
                                     <span id="charCount">0</span>/1000 characters
                                 </small>
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                 <button type="submit" class="btn btn-primary">
                                     <i class="mdi mdi-content-save"></i> Save Note
                                 </button>
                             </div>
                         </form>
                     </div>
                </div>
            </div>
        </div>
        @endif
    
</div>

   <script>
     // Prevent share-modal.js errors
   document.addEventListener('DOMContentLoaded', function() {
       // This prevents the share-modal.js error
       if (typeof window !== 'undefined') {
           window.shareModalInitialized = true;
       }
   });
   
               // Global error handler to prevent script errors from breaking functionality
     window.addEventListener('error', function(e) {
         console.warn('Script error caught:', e.message);
         // Don't let script errors break the page functionality
         e.preventDefault();
     });
     
     // AJAX functions for resend notifications
     function resendEmail(clientId) {
         if (!confirm('Are you sure you want to resend the welcome email?')) {
             return;
         }
         
         const btn = document.getElementById('resendEmailBtn');
         const text = document.getElementById('resendEmailText');
         const icon = btn.querySelector('i');
         
         // Disable button and show loading state
         btn.disabled = true;
         text.textContent = 'Sending...';
         icon.className = 'mdi mdi-loading mdi-spin';
         
         // Send AJAX request
         fetch(`/clients/${clientId}/resend-email`, {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'Accept': 'application/json'
             }
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                 showAlert('success', data.message || 'Email sent successfully!');
             } else {
                 showAlert('error', data.message || 'Failed to send email. Please try again.');
             }
         })
         .catch(error => {
             console.error('Error:', error);
             showAlert('error', 'An error occurred while sending the email. Please try again.');
         })
         .finally(() => {
             // Re-enable button and restore original state
             btn.disabled = false;
             text.textContent = 'Resend Email';
             icon.className = 'mdi mdi-email';
         });
     }
     
     function resendSms(clientId) {
         if (!confirm('Are you sure you want to resend the welcome SMS?')) {
             return;
         }
         
         const btn = document.getElementById('resendSmsBtn');
         const text = document.getElementById('resendSmsText');
         const icon = btn.querySelector('i');
         
         // Disable button and show loading state
         btn.disabled = true;
         text.textContent = 'Sending...';
         icon.className = 'mdi mdi-loading mdi-spin';
         
         // Send AJAX request
         fetch(`/clients/${clientId}/resend-sms`, {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'Accept': 'application/json'
             }
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                 showAlert('success', data.message || 'SMS sent successfully!');
             } else {
                 showAlert('error', data.message || 'Failed to send SMS. Please try again.');
             }
         })
         .catch(error => {
             console.error('Error:', error);
             showAlert('error', 'An error occurred while sending the SMS. Please try again.');
         })
         .finally(() => {
             // Re-enable button and restore original state
             btn.disabled = false;
             text.textContent = 'Resend SMS';
             icon.className = 'mdi mdi-message-text';
         });
     }
     
     // Function to show alerts
     function showAlert(type, message) {
         // Remove any existing alerts
         const existingAlerts = document.querySelectorAll('.alert');
         existingAlerts.forEach(alert => alert.remove());
         
         // Create new alert
         const alertDiv = document.createElement('div');
         alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
         alertDiv.innerHTML = `
             ${message}
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         `;
         
         // Insert alert at the top of the details tab
         const detailsTab = document.getElementById('details');
         detailsTab.insertBefore(alertDiv, detailsTab.firstChild);
         
         // Auto-remove alert after 5 seconds
         setTimeout(() => {
             if (alertDiv.parentNode) {
                 alertDiv.remove();
             }
         }, 5000);
     }
    
         
  
     // Wait for jQuery to be available
   function initializeClientDetails() {
       if (typeof $ === 'undefined' || typeof $.fn === 'undefined') {
           // jQuery not loaded yet, wait a bit and try again
           console.log('jQuery not available, retrying in 100ms...');
           setTimeout(initializeClientDetails, 100);
           return;
       }
     
     $(document).ready(function() {
     // Initialize date picker for date of birth
     $('#date_of_birth_display').datepicker({
         format: 'dd/mm/yyyy',
         autoclose: true,
         todayHighlight: true,
         endDate: '0d', // Cannot select future dates
         clearBtn: true,
         orientation: 'bottom auto'
     }).on('changeDate', function(e) {
         // Convert DD/MM/YYYY to YYYY-MM-DD for the hidden field
         if (e.date) {
             var day = ('0' + e.date.getDate()).slice(-2);
             var month = ('0' + (e.date.getMonth() + 1)).slice(-2);
             var year = e.date.getFullYear();
             var formattedDate = year + '-' + month + '-' + day;
             $('#date_of_birth').val(formattedDate);
         } else {
             $('#date_of_birth').val('');
         }
     }).on('clearDate', function() {
         $('#date_of_birth').val('');
     });
     
     // Handle manual input in the display field
     $('#date_of_birth_display').on('input', function() {
         var value = $(this).val();
         if (value && value.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
             var parts = value.split('/');
             var day = parts[0];
             var month = parts[1];
             var year = parts[2];
             var formattedDate = year + '-' + month + '-' + day;
             $('#date_of_birth').val(formattedDate);
         } else {
             $('#date_of_birth').val('');
         }
     });
     
     // Initialize Select2 for country fields
     $('.select2').select2({
         placeholder: 'Please select a country',
         allowClear: true,
         width: '100%'
     });
     
     // Load countries from JSON file
     $.getJSON('/storage/countries.json', function(data) {
         var countryOptions = '<option value="">Please select a country</option>';
         data.forEach(function(country) {
             countryOptions += '<option value="' + country.name + '">' + country.name + '</option>';
         });
         
         // Populate both country dropdowns
         $('#country_of_birth').html(countryOptions);
         $('#country').html(countryOptions);
         
         // Set values for existing data
         var countryOfBirth = '{{ old("country_of_birth", $client->country_of_birth ?? "") }}';
         var country = '{{ old("country", $client->country ?? "United Kingdom") }}';
         
         if (countryOfBirth) {
             $('#country_of_birth').val(countryOfBirth).trigger('change');
         }
         if (country) {
             $('#country').val(country).trigger('change');
         }
         
         // Re-initialize Select2 after populating options
         $('.select2').select2({
             placeholder: 'Please select a country',
             allowClear: true,
             width: '100%'
         });
     }).fail(function() {
         console.error('Failed to load countries from JSON file');
     });
     
     // Postcode search functionality
     $('#searchPostcode').on('click', function () {
         var postcode = $('#postcode').val();
         var houseNumber = $('#house_number').val();
         var apiKey = 'ak_lvc6urzzSCrvbRGV1L3B7McblBFhZ';
         var url = `https://api.ideal-postcodes.co.uk/v1/postcodes/${postcode}?api_key=${apiKey}`;
         var addressDropdown = $('#address');

         if (!postcode) {
             alert('Please enter a postcode');
             return;
         }

         addressDropdown.empty();
         addressDropdown.append(new Option("Searching...", ""));

         $.getJSON(url, function (data) {
             addressDropdown.empty();
             if (data.result && data.result.length > 0) {
                 addressDropdown.append(new Option("-- Select an address --", ""));
                 
                 // Filter addresses by house number if provided
                 var filteredAddresses = data.result;
                 if (houseNumber) {
                     filteredAddresses = data.result.filter(function(address) {
                         // Check if line_1 starts with the house number
                         return address.line_1 && address.line_1.toLowerCase().startsWith(houseNumber.toLowerCase());
                     });
                 }
                 
                 if (filteredAddresses.length > 0) {
                     filteredAddresses.forEach(function (address) {
                         var label = `${address.line_1}, ${address.post_town}, ${address.postcode}`;
                         addressDropdown.append(new Option(label, JSON.stringify(address)));
                     });
                 } else if (houseNumber) {
                     addressDropdown.append(new Option(`No addresses found for house number ${houseNumber}`, ""));
                     // Show all addresses as fallback
                     data.result.forEach(function (address) {
                         var label = `${address.line_1}, ${address.post_town}, ${address.postcode}`;
                         addressDropdown.append(new Option(label, JSON.stringify(address)));
                     });
                 } else {
                     addressDropdown.append(new Option("No addresses found", ""));
                 }
             } else {
                 addressDropdown.append(new Option("No addresses found", ""));
             }
         }).fail(function() {
             addressDropdown.empty();
             addressDropdown.append(new Option("Error searching for addresses", ""));
         });
     });

    // Auto-fill address fields when address is selected
    $('#address').on('change', function() {
        var selectedAddress = $(this).val();
        if (selectedAddress && selectedAddress !== "") {
            try {
                var address = JSON.parse(selectedAddress);
                
                                 // Fill in the address fields
                 $('#address_line_1').val(address.line_1 || '');
                 $('#address_line_2').val(address.line_2 || '');
                 $('#address_line_3').val(address.line_3 || '');
                 $('#town_city').val(address.post_town || '');
                 $('#county').val(address.county || '');
                 $('#postcode_final').val(address.postcode || '');
                 $('#country').val('United Kingdom').trigger('change');
                
            } catch (e) {
                console.error('Error parsing address data:', e);
            }
        }
    });

         // Allow Enter key to trigger search from both fields
     $('#postcode, #house_number').on('keypress', function(e) {
         if (e.which === 13) { // Enter key
             $('#searchPostcode').click();
         }
     });
     
                       // Initialize notes functionality if client exists
       @if($client)
       console.log('Client exists, initializing notes for client ID:', {{ $client->id ?? 'null' }});
       initializeNotes();
       @else
       console.log('No client found, skipping notes initialization');
       @endif

     });
 }
 
       // Start initialization
    // Wait for DOM to be ready and jQuery to be available
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeClientDetails, 100);
        });
    } else {
        setTimeout(initializeClientDetails, 100);
    }
    
    
 
       // Notes functionality
   function initializeNotes() {
       // Check if jQuery is available
       if (typeof $ === 'undefined') {
           console.log('jQuery not available for notes initialization');
           return;
       }
       
       // Character counter for note content
       $('#noteContent').on('input', function() {
           const maxLength = 1000;
           const currentLength = $(this).val().length;
           const $charCount = $('#charCount');
           
           $charCount.text(currentLength);
           
           if (currentLength > maxLength * 0.9) {
               $charCount.addClass('warning');
               $charCount.removeClass('danger');
           } else if (currentLength > maxLength * 0.95) {
               $charCount.addClass('danger');
               $charCount.removeClass('warning');
           } else {
               $charCount.removeClass('warning danger');
           }
       });
       
       // Clear form when modal is closed
       $('#addNoteModal').on('hidden.bs.modal', function() {
           $('#noteContent').val('');
           $('#charCount').text('0').removeClass('warning danger');
       });
   }

 
 </script>
 
 <style>
 /* Select2 Custom Styling */
 .select2-container--default .select2-selection--single {
     border: 1px solid #ced4da;
     border-radius: 0.375rem;
     height: 38px;
     line-height: 36px;
 }
 
 .select2-container--default .select2-selection--single .select2-selection__rendered {
     line-height: 36px;
     padding-left: 12px;
     color: #495057;
 }
 
 .select2-container--default .select2-selection--single .select2-selection__arrow {
     height: 36px;
 }
 
 .select2-container--default .select2-results__option--highlighted[aria-selected] {
     background-color: #007bff;
 }
 
 .select2-dropdown {
     border: 1px solid #ced4da;
     border-radius: 0.375rem;
 }
 
 .select2-search__field {
     border: 1px solid #ced4da !important;
     border-radius: 0.375rem !important;
 }
 
 /* Reference field styling */
 input[readonly] {
     background-color: #f8f9fa;
     color: #6c757d;
     cursor: not-allowed;
 }
 
 input[readonly]:focus {
     background-color: #f8f9fa;
     border-color: #ced4da;
     box-shadow: none;
 }
 
   /* Notes Styles */
  .note-item {
      border: 1px solid #e9ecef;
      border-radius: 0.5rem;
      padding: 1.25rem;
      margin-bottom: 1rem;
      background-color: #fff;
      transition: all 0.2s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .note-item:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border-color: #dee2e6;
  }

  .note-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 0.75rem;
  }

  .note-meta {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.875rem;
  }

  .note-author {
      font-weight: 600;
      color: #495057;
      background: #f8f9fa;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.8rem;
  }

  .note-date {
      color: #6c757d;
      font-size: 0.8rem;
  }

  .note-content {
      color: #495057;
      line-height: 1.6;
      white-space: pre-wrap;
      word-wrap: break-word;
      font-size: 0.95rem;
  }

  .note-actions {
      display: flex;
      gap: 0.5rem;
  }

  .note-item .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.8rem;
      border-radius: 0.375rem;
  }

 #charCount {
     font-weight: 500;
 }

 #charCount.warning {
     color: #ffc107;
 }

 #charCount.danger {
     color: #dc3545;
 }
 
 /* Date Picker Custom Styling */
 .datepicker {
     border-radius: 0.375rem;
 }
 
 .datepicker table tr td.active,
 .datepicker table tr td.active:hover,
 .datepicker table tr td.active:focus {
     background-color: #007bff;
     border-color: #007bff;
 }
 
 .datepicker table tr td.today {
     background-color: #fff3cd;
     border-color: #ffeaa7;
 }
 
 .datepicker table tr td.today:hover {
     background-color: #ffeaa7;
     border-color: #fdcb6e;
 }
 
 .datepicker-dropdown {
     border: 1px solid #ced4da;
     border-radius: 0.375rem;
     box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
 }
 
 .datepicker table tr td.clear {
     background-color: #6c757d;
     color: white;
 }
 
 .datepicker table tr td.clear:hover {
     background-color: #5a6268;
 }
 </style>

  