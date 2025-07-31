<!-- Document Uploads Tab -->
<div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab" data-client-id="{{ $client->id ?? '' }}">
    <div class="row mt-4">
        <!-- ID Document Section -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">ID Document</h6>
                </div>
                <div class="card-body">
                    <div class="upload-area" data-type="id_document" data-client-id="{{ $client->id ?? '' }}">
                        <div class="upload-zone">
                            <i class="mdi mdi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h6 class="mt-2">Drag & Drop files here</h6>
                            <p class="text-muted">or click to browse</p>
                            <small class="text-muted">Accepted: PDF, JPG, PNG (Max: 20MB)</small>
                        </div>
                        <input type="file" class="file-input" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                    </div>
                    <div class="uploaded-files mt-3" id="id-document-files">
                        <!-- Uploaded files will be displayed here -->
                    </div>
                     
                </div>
            </div>
        </div>

        <!-- Contract Document Section -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Contract Document</h6>
                </div>
                <div class="card-body">
                    <div class="upload-area" data-type="contract_document" data-client-id="{{ $client->id ?? '' }}">
                        <div class="upload-zone">
                            <i class="mdi mdi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h6 class="mt-2">Drag & Drop files here</h6>
                            <p class="text-muted">or click to browse</p>
                            <small class="text-muted">Accepted: PDF, DOC, DOCX (Max: 20MB)</small>
                        </div>
                        <input type="file" class="file-input" accept=".pdf,.doc,.docx" style="display: none;">
                    </div>
                    <div class="uploaded-files mt-3" id="contract-document-files">
                        <!-- Uploaded files will be displayed here -->
                    </div>
                     
                </div>
            </div>
        </div>

        <!-- Financial Document Section -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Financial Document</h6>
                </div>
                <div class="card-body">
                    <div class="upload-area" data-type="financial_document" data-client-id="{{ $client->id ?? '' }}">
                        <div class="upload-zone">
                            <i class="mdi mdi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h6 class="mt-2">Drag & Drop files here</h6>
                            <p class="text-muted">or click to browse</p>
                            <small class="text-muted">Accepted: PDF, XLSX, XLS (Max: 20MB)</small>
                        </div>
                        <input type="file" class="file-input" accept=".pdf,.xlsx,.xls" style="display: none;">
                    </div>
                    <div class="uploaded-files mt-3" id="financial-document-files">
                        <!-- Uploaded files will be displayed here -->
                    </div>
                     
                </div>
            </div>
        </div>

        <!-- Other Documents Section -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Other Documents</h6>
                </div>
                <div class="card-body">
                    <div class="upload-area" data-type="other_documents" data-client-id="{{ $client->id ?? '' }}">
                        <div class="upload-zone">
                            <i class="mdi mdi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h6 class="mt-2">Drag & Drop files here</h6>
                            <p class="text-muted">or click to browse</p>
                            <small class="text-muted">Accepted: PDF, DOC, DOCX, JPG, PNG, XLSX, XLS (Max: 20MB)</small>
                        </div>
                        <input type="file" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls" style="display: none;">
                    </div>
                    <div class="uploaded-files mt-3" id="other-documents-files">
                        <!-- Uploaded files will be displayed here -->
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div class="modal fade" id="documentViewModal" tabindex="-1" aria-labelledby="documentViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentViewModalLabel">Document Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="documentViewer">
                    <!-- Document content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="downloadDocumentBtn">
                    <i class="mdi mdi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background-color: #f8f9fa;
    position: relative;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
}

.upload-area.dragover {
    border-color: #28a745;
    background-color: #d4edda;
    transform: scale(1.02);
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
}

.upload-area.dragover .upload-zone {
    color: #155724;
}

.upload-area.dragover .mdi-cloud-upload {
    color: #28a745 !important;
    transform: scale(1.1);
}

.upload-zone {
    pointer-events: none;
}

.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
    background-color: #fff;
}

.file-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.file-icon {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    color: #6c757d;
}

.file-details h6 {
    margin: 0;
    font-size: 0.9rem;
    color: #495057;
}

.file-details small {
    color: #6c757d;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

.upload-progress {
    width: 100%;
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.upload-progress-bar {
    height: 100%;
    background-color: #007bff;
    transition: width 0.3s ease;
}

.upload-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

#documentViewer {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#documentViewer img {
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
}

#documentViewer iframe {
    width: 100%;
    height: 500px;
    border: none;
}
</style>

<script>
// Wait for jQuery to be available
function waitForJQuery(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForJQuery(callback);
        }, 100);
    }
}

waitForJQuery(function() {
    $(document).ready(function() {
        console.log('Document ready, initializing drag and drop...');
        
        // Initialize drag and drop functionality
        initializeDragAndDrop();
        
        // Load existing documents if client exists
        const clientId = $('.upload-area').first().data('client-id');
        console.log('Client ID found:', clientId);
        if (clientId) {
            loadClientDocuments(clientId);
        }
    });
});

function initializeDragAndDrop() {
    console.log('Initializing drag and drop for', $('.upload-area').length, 'upload areas');
    
    $('.upload-area').each(function(index) {
        const $uploadArea = $(this);
        const $fileInput = $uploadArea.find('.file-input');
        const documentType = $uploadArea.data('type');
        const clientId = $uploadArea.data('client-id');
        
        console.log(`Upload area ${index + 1}:`, {
            type: documentType,
            clientId: clientId,
            element: $uploadArea[0]
        });
        
        // Click to upload
        $uploadArea.on('click', function(e) {
            // Prevent click when clicking on child elements
            if (e.target === this || $(e.target).hasClass('upload-area')) {
                $fileInput.click();
            }
        });
        
        // File input change
        $fileInput.on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadFile(file, documentType, clientId, $uploadArea);
            }
        });
        
        // Drag and drop events
        $uploadArea.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadArea.addClass('dragover');
            console.log('Drag over detected');
        });
        
        $uploadArea.on('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadArea.addClass('dragover');
            console.log('Drag enter detected');
        });
        
        $uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Only remove class if we're leaving the upload area completely
            if (!$(e.currentTarget).has(e.relatedTarget).length) {
                $uploadArea.removeClass('dragover');
                console.log('Drag leave detected');
            }
        });
        
        $uploadArea.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadArea.removeClass('dragover');
            console.log('Drop detected');
            
            const files = e.originalEvent.dataTransfer.files;
            console.log('Dropped files:', files);
            if (files.length > 0) {
                uploadFile(files[0], documentType, clientId, $uploadArea);
            }
        });
        
        // Prevent default drag behaviors on the page
        $(document).on('dragover', function(e) {
            e.preventDefault();
        });
        
        $(document).on('drop', function(e) {
            e.preventDefault();
        });
        
        // Add a visual indicator that the area is ready
        $uploadArea.append('<div class="upload-ready-indicator" style="position: absolute; top: 5px; right: 5px; background: #28a745; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px;">Ready</div>');
    });
    
    console.log('Drag and drop initialization complete');
}

function uploadFile(file, documentType, clientId, $uploadArea) {
    console.log('Uploading file:', file.name, 'Type:', documentType, 'Client ID:', clientId);
    
    if (!clientId) {
        alert('Please save the client first before uploading documents.');
        return;
    }
    
    // Validate file size (20MB)
    if (file.size > 20 * 1024 * 1024) {
        alert('File size must be less than 20MB.');
        return;
    }
    
    // Validate file type based on document type
    const allowedTypes = {
        'id_document': ['.pdf', '.jpg', '.jpeg', '.png'],
        'contract_document': ['.pdf', '.doc', '.docx'],
        'financial_document': ['.pdf', '.xlsx', '.xls'],
        'other_documents': ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png', '.xlsx', '.xls']
    };
    
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
    if (!allowedTypes[documentType].includes(fileExtension)) {
        alert(`File type not allowed for ${documentType.replace('_', ' ')}. Allowed: ${allowedTypes[documentType].join(', ')}`);
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('file', file);
    formData.append('document_type', documentType);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    // Show upload progress
    const $progressContainer = $uploadArea.find('.uploaded-files');
    const progressId = 'progress-' + Date.now();
    const $progressHtml = $(`
        <div class="file-item" id="${progressId}">
            <div class="file-info">
                <div class="file-icon">
                    <i class="mdi mdi-file"></i>
                </div>
                <div class="file-details">
                    <h6>${file.name}</h6>
                    <small>Uploading...</small>
                </div>
            </div>
            <div class="upload-progress">
                <div class="upload-progress-bar" style="width: 0%"></div>
            </div>
        </div>
    `);
    $progressContainer.prepend($progressHtml);
    
    // Upload file
    $.ajax({
        url: `/clients/${clientId}/documents/upload`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    $(`#${progressId} .upload-progress-bar`).css('width', percentComplete + '%');
                }
            });
            return xhr;
        },
        success: function(response) {
            if (response.success) {
                $(`#${progressId}`).remove();
                loadClientDocuments(clientId);
                showAlert('Document uploaded successfully!', 'success');
            }
        },
        error: function(xhr) {
            $(`#${progressId}`).remove();
            const error = xhr.responseJSON?.message || 'Upload failed. Please try again.';
            showAlert(error, 'error');
        }
    });
}

function loadClientDocuments(clientId) {
    $.get(`/clients/${clientId}/documents`, function(response) {
        if (response.success) {
            displayDocuments(response.documents);
        }
    });
}

function displayDocuments(documents) {
    // Clear all file containers
    $('.uploaded-files').empty();
    
    // Group documents by type
    const documentsByType = {};
    documents.forEach(doc => {
        if (!documentsByType[doc.document_type]) {
            documentsByType[doc.document_type] = [];
        }
        documentsByType[doc.document_type].push(doc);
    });
    
    // Display documents for each type
    Object.keys(documentsByType).forEach(type => {
        const containerId = type.replace('_', '-') + '-files';
        const $container = $(`#${containerId}`);
        
        documentsByType[type].forEach(doc => {
            const fileHtml = createFileItemHtml(doc);
            $container.append(fileHtml);
        });
    });
}

function createFileItemHtml(doc) {
    const iconClass = getFileIcon(doc.original_name);
    const uploadDate = new Date(doc.created_at).toLocaleDateString();
    
    return $(`
        <div class="file-item" data-document-id="${doc.id}">
            <div class="file-info">
                <div class="file-icon">
                    <i class="mdi ${iconClass}"></i>
                </div>
                <div class="file-details">
                    <h6>${doc.original_name}</h6>
                    <small>${doc.file_size_human} • Uploaded ${uploadDate}</small>
                </div>
            </div>
            <div class="file-actions">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewDocument(${doc.id}, '${doc.original_name}')">
                    <i class="mdi mdi-eye"></i> View
                </button>
                <a href="/documents/${doc.id}/download" class="btn btn-sm btn-outline-success">
                    <i class="mdi mdi-download"></i> Download
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument(${doc.id})">
                    <i class="mdi mdi-delete"></i> Delete
                </button>
            </div>
        </div>
    `);
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const iconMap = {
        'pdf': 'mdi-file-pdf-box',
        'doc': 'mdi-file-word-box',
        'docx': 'mdi-file-word-box',
        'xls': 'mdi-file-excel-box',
        'xlsx': 'mdi-file-excel-box',
        'jpg': 'mdi-file-image-box',
        'jpeg': 'mdi-file-image-box',
        'png': 'mdi-file-image-box'
    };
    return iconMap[ext] || 'mdi-file';
}

function viewDocument(documentId, filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(ext);
    const isPdf = ext === 'pdf';
    
    if (isImage || isPdf) {
        const viewerUrl = `/documents/${documentId}/view`;
        const downloadUrl = `/documents/${documentId}/download`;
        
        $('#documentViewer').empty();
        
        if (isImage) {
            $('#documentViewer').html(`<img src="${viewerUrl}" alt="${filename}">`);
        } else if (isPdf) {
            $('#documentViewer').html(`<iframe src="${viewerUrl}"></iframe>`);
        }
        
        $('#downloadDocumentBtn').attr('href', downloadUrl);
        $('#documentViewModal').modal('show');
    } else {
        // For other file types, just download
        window.open(`/documents/${documentId}/download`, '_blank');
    }
}

function deleteDocument(documentId) {
    if (confirm('Are you sure you want to delete this document?')) {
        $.ajax({
            url: `/documents/${documentId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`[data-document-id="${documentId}"]`).remove();
                    showAlert('Document deleted successfully!', 'success');
                }
            },
            error: function() {
                showAlert('Failed to delete document. Please try again.', 'error');
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
    
    // Add new alert at the top of the documents tab
    $('#documents').prepend(alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Test function for debugging - can be called from browser console
window.testDragAndDrop = function() {
    console.log('Testing drag and drop functionality...');
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('Upload areas found:', $('.upload-area').length);
    console.log('Upload areas:', $('.upload-area'));
    
    $('.upload-area').each(function(index) {
        const $area = $(this);
        console.log(`Area ${index + 1}:`, {
            type: $area.data('type'),
            clientId: $area.data('client-id'),
            hasFileInput: $area.find('.file-input').length > 0,
            element: $area[0]
        });
    });
    
    return 'Drag and drop test completed. Check console for details.';
};
</script> 