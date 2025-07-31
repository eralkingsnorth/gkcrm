# Client Notification System Setup

This document explains how to set up and configure the email and SMS notification system for new client creation.

## Overview

When a new client is created (via API or web interface), the system automatically sends:
1. **Welcome Email** - HTML email with LOA signature link via Outlook API
2. **Welcome SMS** - Text message with case reference and signature link via VoodooSMS

## Configuration

### 1. Environment Variables

Add the following variables to your `.env` file:

```env
# Outlook API Configuration
OUTLOOK_API_URL=https://graph.microsoft.com/v1.0
OUTLOOK_ACCESS_TOKEN=your_outlook_access_token_here
OUTLOOK_CLIENT_ID=your_outlook_client_id_here
OUTLOOK_CLIENT_SECRET=your_outlook_client_secret_here
OUTLOOK_TENANT_ID=your_outlook_tenant_id_here

# VoodooSMS Configuration
VOODOOSMS_API_URL=https://api.voodoosms.com
VOODOOSMS_API_KEY=your_voodoosms_api_key_here
VOODOOSMS_FROM=Kingsnorth

# Application URL (used for generating signature links)
APP_URL=https://goldmanknightley.co.uk
```

### 2. Outlook API Setup

#### Prerequisites
- Microsoft 365 account with admin access
- Azure AD application registration

#### Steps to get Outlook API credentials:

1. **Register Azure AD Application**:
   - Go to Azure Portal → Azure Active Directory → App registrations
   - Click "New registration"
   - Name: "GKCRM Email Service"
   - Supported account types: "Accounts in this organizational directory only"
   - Click "Register"

2. **Configure API Permissions**:
   - Go to "API permissions"
   - Click "Add a permission"
   - Select "Microsoft Graph"
   - Choose "Application permissions"
   - Add "Mail.Send" permission
   - Click "Grant admin consent"

3. **Create Client Secret**:
   - Go to "Certificates & secrets"
   - Click "New client secret"
   - Add description and set expiration
   - Copy the secret value

4. **Get Access Token**:
   ```bash
   curl -X POST "https://login.microsoftonline.com/{TENANT_ID}/oauth2/v2.0/token" \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "client_id={CLIENT_ID}&client_secret={CLIENT_SECRET}&scope=https://graph.microsoft.com/.default&grant_type=client_credentials"
   ```

5. **Update .env file** with the obtained values.

### 3. VoodooSMS Setup

#### Prerequisites
- VoodooSMS account
- API key from VoodooSMS dashboard

#### Steps:
1. Sign up/login to VoodooSMS
2. Go to API settings in your dashboard
3. Generate or copy your API key
4. Update the `VOODOOSMS_API_KEY` in your `.env` file

## Email Template

The system uses a professional HTML email template that includes:

- **Header**: Goldman Knightley branding with PCP Claims Team
- **Personalized greeting**: Client's first name
- **LOA signature request**: Clear explanation of what's needed
- **Signature button**: Direct link to sign LOA
- **Legal disclaimer**: Required compliance text
- **Footer**: Contact information

### Email Content Structure:
```
Subject: Welcome to Goldman Knightley - Sign Your LOA

Hi [First Name],

Thank you for submitting your claim. To continue, we need you to electronically sign your Letter of Authority (LOA).

This link is personalized for you: [Full Name].

[Sign LOA Now Button]

[Legal Disclaimer]

PCP Claims Team
Goldman Knightley
```

## SMS Template

The SMS message follows this format:

```
Dear [First Name],

With case reference [FD######]

Please visit the link below to sign the required documentation to progress your claim.

[Short URL]

Regards,
Kingsnorth Solicitors
```

## API Response

When a client is created via API, the response includes notification status:

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
        // ... other client data
    },
    "notifications": {
        "email_sent": true,
        "sms_sent": true
    }
}
```

If notifications fail, error details are included:

```json
{
    "notifications": {
        "email_sent": false,
        "sms_sent": true,
        "email_error": "Failed to send email: Invalid access token"
    }
}
```

## Web Interface

When creating clients via the web interface, success messages include notification status:

- "Client created successfully. Welcome email sent. Welcome SMS sent."
- "Client created successfully. Welcome email sent." (if SMS fails)
- "Client created successfully." (if both fail)

## Testing

### Test Email Service
```php
use App\Services\EmailService;

$emailService = new EmailService();
$client = Client::find(1);
$result = $emailService->sendWelcomeEmail($client);
```

### Test SMS Service
```php
use App\Services\SmsService;

$smsService = new SmsService();
$client = Client::find(1);
$result = $smsService->sendWelcomeSms($client);
```

### Test Both Services
```php
use App\Services\ClientNotificationService;

$notificationService = new ClientNotificationService(
    new EmailService(),
    new SmsService()
);
$client = Client::find(1);
$results = $notificationService->sendWelcomeNotifications($client);
```

## Troubleshooting

### Common Email Issues:
1. **Invalid access token**: Regenerate the Outlook access token
2. **Permission denied**: Ensure "Mail.Send" permission is granted
3. **Rate limiting**: Implement retry logic for high-volume scenarios

### Common SMS Issues:
1. **Invalid API key**: Check VoodooSMS API key in .env
2. **Invalid phone number**: Ensure phone numbers are in international format
3. **Insufficient credits**: Check VoodooSMS account balance

### Logs
All notification attempts are logged in Laravel's log files:
- Success: `Welcome email sent successfully to client: 123`
- Errors: `Failed to send welcome email: [error details]`

## Security Considerations

1. **API Keys**: Store all API keys securely in environment variables
2. **Access Tokens**: Rotate Outlook access tokens regularly
3. **Rate Limiting**: Implement appropriate rate limiting for API endpoints
4. **Data Privacy**: Ensure client data is handled according to GDPR requirements

## Future Enhancements

1. **URL Shortening**: Integrate with a URL shortening service for SMS links
2. **Template Management**: Create admin interface for email/SMS template management
3. **Delivery Tracking**: Implement delivery and read receipts
4. **Retry Logic**: Add automatic retry for failed notifications
5. **Queue System**: Move notifications to background jobs for better performance 