# Quick Fix for Notification Issues

## Immediate Steps to Fix the Problem

### Step 1: Check Your .env File

Make sure your `.env` file has these settings:

```env
# Laravel App Key (required for encryption)
APP_KEY=base64:your_32_character_key_here

# Outlook API Settings
OUTLOOK_API_URL=https://graph.microsoft.com/v1.0
OUTLOOK_ACCESS_TOKEN=your_actual_access_token_here
OUTLOOK_CLIENT_ID=4fe41997-efb4-4607-adfd-4158deedf78c
OUTLOOK_CLIENT_SECRET=uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz
OUTLOOK_TENANT_ID=07f5987e-d351-4de4-9c7a-4525be53ff1a

# VoodooSMS Settings
VOODOOSMS_API_URL=https://api.voodoosms.com
VOODOOSMS_API_KEY=5coEhAV8HLfTg5HmiWjkH5DY6qhdiNBerqiOq5NxGtGnoO
VOODOOSMS_FROM=Kingsnorth

# App URL
APP_URL=https://goldmanknightley.co.uk
```

### Step 2: Get Outlook Access Token

The most common issue is missing Outlook access token. Run this command:

```bash
curl -X POST "https://login.microsoftonline.com/07f5987e-d351-4de4-9c7a-4525be53ff1a/oauth2/v2.0/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=client_credentials&client_id=4fe41997-efb4-4607-adfd-4158deedf78c&client_secret=uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz&scope=https://graph.microsoft.com/.default"
```

Copy the `access_token` from the response and add it to your `.env`:

```env
OUTLOOK_ACCESS_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik5HVEZ2ZEstZnl0aEV1Q...
```

### Step 3: Clear Laravel Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 4: Run Debug Script

```bash
php debug_notifications.php
```

This will tell you exactly what's wrong.

### Step 5: Test the Fix

Create a new client through the API or web interface to test if notifications work.

## Common Issues and Solutions

### Issue: "Email service returned false"
**Solution:** Missing or invalid Outlook access token

### Issue: "SMS service returned false"  
**Solution:** Missing or invalid VoodooSMS API key

### Issue: "Service not available"
**Solution:** Clear Laravel cache and restart application

### Issue: "Decryption failed"
**Solution:** Generate new APP_KEY with `php artisan key:generate`

## Quick Test Commands

### Test Outlook API:
```bash
curl -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  https://graph.microsoft.com/v1.0/me
```

### Test VoodooSMS API:
```bash
curl -H "Authorization: Bearer 5coEhAV8HLfTg5HmiWjkH5DY6qhdiNBerqiOq5NxGtGnoO" \
  https://api.voodoosms.com/balance
```

## If Still Not Working

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Run the debug script: `php debug_notifications.php`
3. Verify all environment variables are set correctly
4. Test APIs manually with cURL
5. Restart your web server

## Expected Behavior After Fix

When you create a new client, you should see:

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

And the client should receive:
- **Email**: HTML email with signature link
- **SMS**: Text message with case reference and signature link 