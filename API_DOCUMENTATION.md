# Client API Documentation

## Overview
This API provides comprehensive CRUD operations for client management in the GKCRM system. All endpoints require API key authentication except for the signature endpoint.

## Authentication
All API requests (except signature endpoint) require an API key to be included in the request headers or as a query parameter:

**Header Method:**
```
X-API-Key: your_api_key_here
```

**Query Parameter Method:**
```
?api_key=your_api_key_here
```

## Base URL
```
https://yourdomain.com/api
```

## Endpoints

### 1. List Clients
**GET** `/clients`

**Parameters:**
- `page` (optional): Page number for pagination (default: 1)
- `per_page` (optional): Number of items per page (default: 15, max: 100)
- `search` (optional): Search term to filter clients by name, email, or mobile number
- `status` (optional): Filter by client status (active, inactive, pending, suspended)
- `lead_source` (optional): Filter by lead source

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/api/clients?page=1&per_page=10" \
  -H "X-API-Key: your_api_key_here"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "lead_source": "website",
      "title": "Mr",
      "forename": "John",
      "surname": "Doe",
      "date_of_birth": "1990-05-15",
      "country_of_birth": "United Kingdom",
      "marital_status": "single",
      "email_address": "john.doe@example.com",
      "mobile_number": "07123456789",
      "home_phone": "01234567890",
      "postcode": "SW1A 1AA",
      "house_number": "123",
      "address_line_1": "Test Street",
      "address_line_2": null,
      "address_line_3": null,
      "town_city": "London",
      "county": "Greater London",
      "country": "United Kingdom",
      "other": null,
      "notes": "Test client",
      "client_status": "active",
      "created_at": "2025-07-31T10:00:00.000000Z",
      "updated_at": "2025-07-31T10:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 1,
    "last_page": 1
  }
}
```

### 2. Create Client
**POST** `/clients`

**Required Fields:**
- `forename`: Client's first name
- `surname`: Client's last name
- `email_address`: Client's email address
- `mobile_number`: Client's mobile number

**Optional Fields:**
- `lead_source`: Source of the lead
- `title`: Title (Mr, Mrs, Miss, Ms, Dr, Prof, Sir, Lady)
- `date_of_birth`: Date of birth (YYYY-MM-DD)
- `country_of_birth`: Country of birth
- `marital_status`: Marital status (single, married, divorced, widowed, civil_partnership, separated)
- `home_phone`: Home phone number
- `postcode`: Postal code
- `house_number`: House number
- `address_line_1`: First line of address
- `address_line_2`: Second line of address
- `address_line_3`: Third line of address
- `town_city`: Town or city
- `county`: County
- `country`: Country
- `other`: Additional information
- `notes`: Client notes
- `client_status`: Client status (active, inactive, pending, suspended)

**Example Request:**
```bash
curl -X POST "https://yourdomain.com/api/clients" \
  -H "X-API-Key: your_api_key_here" \
  -H "Content-Type: application/json" \
  -d @sample_post_request.json
```

**Example Response:**
```json
{
  "success": true,
  "message": "Client created successfully",
  "data": {
    "id": 1,
    "forename": "John",
    "surname": "Doe",
    "email_address": "john.doe@example.com",
    "mobile_number": "07123456789",
    "client_status": "active",
    "created_at": "2025-07-31T10:00:00.000000Z",
    "updated_at": "2025-07-31T10:00:00.000000Z"
  },
  "notifications": {
    "email_sent": true,
    "sms_sent": true
  }
}
```

### 3. Get Client Details
**GET** `/clients/{id}`

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/api/clients/1" \
  -H "X-API-Key: your_api_key_here"
```

### 4. Update Client
**PUT** `/clients/{id}`

**Optional Fields (same as Create Client):**
- All fields from Create Client endpoint
- `files[]`: Array of files to upload
- `document_types[]`: Array of document types for each file (id_document, contract_document, financial_document, other_documents)
- `file_descriptions[]`: Array of descriptions for each file
- `new_note`: New note content to add to the client

**Example Request:**
```bash
curl -X PUT "https://yourdomain.com/api/clients/1" \
  -H "X-API-Key: your_api_key_here" \
  -H "Content-Type: multipart/form-data" \
  -F "forename=John" \
  -F "surname=Smith" \
  -F "email_address=john.smith@example.com" \
  -F "files[]=@document1.pdf" \
  -F "document_types[]=id_document" \
  -F "file_descriptions[]=Passport copy" \
  -F "files[]=@document2.pdf" \
  -F "document_types[]=contract_document" \
  -F "file_descriptions[]=Service agreement" \
  -F "new_note=Client updated with new documents"
```

**Example Response:**
```json
{
  "success": true,
  "message": "Client updated successfully",
  "data": {
    "id": 1,
    "forename": "John",
    "surname": "Smith",
    "email_address": "john.smith@example.com",
    "client_status": "active",
    "created_at": "2025-07-31T10:00:00.000000Z",
    "updated_at": "2025-07-31T10:00:00.000000Z",
    "client_notes": [...],
    "documents": [...]
  },
  "uploaded_files": [
    {
      "id": 1,
      "client_id": 1,
      "document_type": "id_document",
      "original_name": "document1.pdf",
      "file_name": "1732982400_abc123def4.pdf",
      "mime_type": "application/pdf",
      "file_size": 1024000,
      "description": "Passport copy",
      "created_at": "2025-07-31T10:00:00.000000Z"
    }
  ],
  "new_note": {
    "id": 1,
    "client_id": 1,
    "content": "Client updated with new documents",
    "created_by": 1,
    "created_at": "2025-07-31T10:00:00.000000Z",
    "creator": {
      "id": 1,
      "name": "Admin User"
    }
  }
}
```

### 5. Upload Client Document
**POST** `/clients/{id}/documents/upload`

**Required Fields:**
- `document_type`: Type of document (id_document, contract_document, financial_document, other_documents)
- `file`: File to upload (PDF, JPG, JPEG, PNG, DOC, DOCX, XLSX, XLS, max 20MB)

**Optional Fields:**
- `description`: Description of the document

**Example Request:**
```bash
curl -X POST "https://yourdomain.com/api/clients/1/documents/upload" \
  -H "X-API-Key: your_api_key_here" \
  -H "Content-Type: multipart/form-data" \
  -F "document_type=id_document" \
  -F "file=@passport.pdf" \
  -F "description=Passport copy"
```

**Example Response:**
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "data": {
    "id": 1,
    "client_id": 1,
    "document_type": "id_document",
    "original_name": "passport.pdf",
    "file_name": "1732982400_abc123def4.pdf",
    "mime_type": "application/pdf",
    "file_size": 1024000,
    "description": "Passport copy",
    "created_at": "2025-07-31T10:00:00.000000Z",
    "client": {
      "id": 1,
      "forename": "John",
      "surname": "Doe"
    }
  }
}
```

### 6. Get Client Documents
**GET** `/clients/{id}/documents`

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/api/clients/1/documents" \
  -H "X-API-Key: your_api_key_here"
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "client_id": 1,
      "document_type": "id_document",
      "original_name": "passport.pdf",
      "file_name": "1732982400_abc123def4.pdf",
      "mime_type": "application/pdf",
      "file_size": 1024000,
      "description": "Passport copy",
      "created_at": "2025-07-31T10:00:00.000000Z",
      "file_size_human": "1000 KB",
      "is_image": false,
      "is_pdf": true,
      "document_type_display": "ID Document"
    }
  ]
}
```

### 7. Delete Client Document
**DELETE** `/documents/{documentId}`

**Example Request:**
```bash
curl -X DELETE "https://yourdomain.com/api/documents/1" \
  -H "X-API-Key: your_api_key_here"
```

**Example Response:**
```json
{
  "success": true,
  "message": "Document deleted successfully"
}
```

### 8. Delete Client
**DELETE** `/clients/{id}`

**Example Request:**
```bash
curl -X DELETE "https://yourdomain.com/api/clients/1" \
  -H "X-API-Key: your_api_key_here"
```

### 6. Get Client Details by Encrypted ID (Signature Link)
**GET** `/clients/signature/{encryptedId}`

This endpoint is used to retrieve client details for signature pages. No API key is required.

**Example Request:**
```bash
curl -X GET "https://yourdomain.com/api/clients/signature/abc123xyz"
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "forename": "John",
    "surname": "Doe",
    "email_address": "john.doe@example.com",
    "mobile_number": "07123456789",
    "date_of_birth": "1990-05-15",
    "address": "123 Test Street, London, SW1A 1AA",
    "case_reference": "FD000001"
  }
}
```

## Email and SMS Notifications

When a new client is created, the system automatically sends:

1. **Welcome Email**: HTML email with signature link
2. **Welcome SMS**: Text message with signature link

### Signature URLs
The system generates encrypted signature URLs in the format:
```
https://goldmanknightley.co.uk/submit-signature/{encryptedId}
```

The `encryptedId` is an encrypted version of the client ID for security. When accessed, the signature page can retrieve client details using the `/api/clients/signature/{encryptedId}` endpoint.

### Notification Response
The client creation response includes notification status:

```json
{
  "success": true,
  "message": "Client created successfully",
  "data": { ... },
  "notifications": {
    "email_sent": true,
    "sms_sent": true
  }
}
```

If notifications fail, error details are included:

```json
{
  "success": true,
  "message": "Client created successfully",
  "data": { ... },
  "notifications": {
    "email_sent": false,
    "sms_sent": true,
    "email_error": "Failed to send email: Invalid access token"
  }
}
```

## Error Responses

### Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email_address": ["The email address field is required."],
    "forename": ["The forename field is required."]
  }
}
```

### Not Found Error
```json
{
  "success": false,
  "message": "Client not found"
}
```

### Server Error
```json
{
  "success": false,
  "message": "Failed to create client",
  "error": "Database connection error"
}
```

## Sample Request Files

- `sample_post_request.json`: Complete client creation example
- `minimal_post_request.json`: Minimal required fields example

## Testing

Use the provided `test_api.php` script to test all endpoints:

```bash
php test_api.php
```

Make sure to update the API key and base URL in the test script before running. 