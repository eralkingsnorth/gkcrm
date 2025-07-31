# Client Notes API Documentation

## Overview
The Client API now supports creating and managing client notes. You can add notes when creating a client or manage them separately.

## Creating a Client with Notes

### Endpoint
`POST /api/clients`

### Request Body
```json
{
    "forename": "John",
    "surname": "Doe",
    "email_address": "john.doe@example.com",
    "mobile_number": "07123456789",
    "client_notes": [
        {
            "content": "Initial consultation completed. Client seems interested in debt management."
        },
        {
            "content": "Documents requested: bank statements, payslips, and utility bills."
        }
    ]
}
```

### Response
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
        "client_notes": [
            {
                "id": 1,
                "content": "Initial consultation completed. Client seems interested in debt management.",
                "created_by": 1,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z",
                "creator": {
                    "id": 1,
                    "name": "Admin User"
                }
            },
            {
                "id": 2,
                "content": "Documents requested: bank statements, payslips, and utility bills.",
                "created_by": 1,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z",
                "creator": {
                    "id": 1,
                    "name": "Admin User"
                }
            }
        ]
    }
}
```

## Managing Client Notes Separately

### Get Client Notes
`GET /api/clients/{client_id}/notes`

### Add a Note to Existing Client
`POST /api/clients/{client_id}/notes`

**Request Body:**
```json
{
    "content": "Client called to follow up on document submission."
}
```

### Delete a Note
`DELETE /api/notes/{note_id}`

## Validation Rules

- `client_notes` (optional): Array of note objects
- `client_notes.*.content` (required if client_notes provided): String, max 1000 characters
- `content` (for individual notes): Required string, max 1000 characters

## Notes

- Notes are automatically associated with the authenticated user (or user ID 1 if no authentication)
- Notes are ordered by creation date (newest first)
- All API endpoints require the `api.key` middleware
- The `created_by` field references the user who created the note 