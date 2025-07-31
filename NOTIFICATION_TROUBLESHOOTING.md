# Notification System Troubleshooting Guide

## Common Issues and Solutions

### 1. "Failed to send email" Error

**Possible Causes:**
- Missing Outlook access token
- Invalid API credentials
- Network connectivity issues
- Incorrect API endpoint

**Solutions:**

#### A. Check Outlook Configuration
Make sure your `.env` file has the correct Outlook settings:

```env
OUTLOOK_API_URL=https://graph.microsoft.com/v1.0
OUTLOOK_ACCESS_TOKEN=your_actual_access_token_here
OUTLOOK_CLIENT_ID=4fe41997-efb4-4607-adfd-4158deedf78c
OUTLOOK_CLIENT_SECRET=uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz
OUTLOOK_TENANT_ID=07f5987e-d351-4de4-9c7a-4525be53ff1a
```

#### B. Get Outlook Access Token
You need to obtain an access token for the Outlook API. Follow these steps:

1. **Using Azure Portal:**
   - Go to Azure Portal → Azure Active Directory → App registrations
   - Find your app (Client ID: `4fe41997-efb4-4607-adfd-4158deedf78c`)
   - Go to "Certificates & secrets" → "New client secret"
   - Copy the secret value

2. **Using Postman or cURL:**
```bash
curl -X POST "https://login.microsoftonline.com/07f5987e-d351-4de4-9c7a-4525be53ff1a/oauth2/v2.0/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "grant_type=client_credentials&client_id=4fe41997-efb4-4607-adfd-4158deedf78c&client_secret=uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz&scope=https://graph.microsoft.com/.default"
```

3. **Add the token to your .env:**
```env
OUTLOOK_ACCESS_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik5HVEZ2ZEstZnl0aEV1Q...
```

### 2. "Failed to send SMS" Error

**Possible Causes:**
- Missing VoodooSMS API key
- Invalid API credentials
- Incorrect phone number format
- API endpoint issues

**Solutions:**

#### A. Check VoodooSMS Configuration
Verify your `.env` file has the correct VoodooSMS settings:

```env
VOODOOSMS_API_URL=https://api.voodoosms.com
VOODOOSMS_API_KEY=5coEhAV8HLfTg5HmiWjkH5DY6qhdiNBerqiOq5NxGtGnoO
VOODOOSMS_FROM=Kingsnorth
```

#### B. Test VoodooSMS API
Test the API directly:

```bash
curl -X POST "https://api.voodoosms.com/send" \
  -H "Authorization: Bearer 5coEhAV8HLfTg5HmiWjkH5DY6qhdiNBerqiOq5NxGtGnoO" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "447123456789",
    "from": "Kingsnorth",
    "message": "Test message"
  }'
```

### 3. Service Dependency Issues

**Symptoms:**
- "Email service not available" error
- "SMS service not available" error
- Dependency injection failures

**Solutions:**

#### A. Clear Laravel Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

#### B. Check Service Registration
The services are registered in `app/Providers/AppServiceProvider.php`. Make sure this file is correct.

#### C. Restart Application
If using a web server, restart it to ensure new configurations are loaded.

### 4. URL Generation Issues

**Symptoms:**
- Invalid signature URLs
- Decryption failures
- "Client not found" errors

**Solutions:**

#### A. Check APP_KEY
Make sure your Laravel `APP_KEY` is set in `.env`:

```env
APP_KEY=base64:your_32_character_key_here
```

#### B. Generate APP_KEY if missing
```bash
php artisan key:generate
```

### 5. Testing the System

#### A. Run the Test Script
Use the provided test script to diagnose issues:

```bash
php test_notifications.php
```

This will:
- Check all configurations
- Test service instantiation
- Verify URL generation
- Test actual notifications (if you confirm)

#### B. Check Laravel Logs
Check the Laravel logs for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

### 6. Manual Testing

#### A. Test Email Service Only
```php
// In tinker or a test script
$emailService = app(\App\Services\EmailService::class);
$client = \App\Models\Client::first();
$result = $emailService->sendWelcomeEmail($client);
var_dump($result);
```

#### B. Test SMS Service Only
```php
// In tinker or a test script
$smsService = app(\App\Services\SmsService::class);
$client = \App\Models\Client::first();
$result = $smsService->sendWelcomeSms($client);
var_dump($result);
```

### 7. Environment-Specific Issues

#### A. Development Environment
- Make sure your `.env` file is in the project root
- Check that `APP_ENV=local` is set
- Ensure all required environment variables are present

#### B. Production Environment
- Verify all environment variables are set on the server
- Check server logs for errors
- Ensure proper file permissions

### 8. Common Error Messages and Solutions

| Error Message | Likely Cause | Solution |
|---------------|--------------|----------|
| "Email service not available" | Service not registered | Check AppServiceProvider |
| "SMS service not available" | Service not registered | Check AppServiceProvider |
| "Failed to send email" | Missing access token | Get Outlook access token |
| "Failed to send SMS" | Invalid API key | Verify VoodooSMS credentials |
| "Client not found" | Invalid encrypted ID | Check URL generation |
| "Decryption failed" | Missing APP_KEY | Generate APP_KEY |

### 9. Getting Help

If you're still experiencing issues:

1. Run the test script and share the output
2. Check the Laravel logs for specific error messages
3. Verify all environment variables are correctly set
4. Test the APIs manually using cURL or Postman
5. Ensure your Laravel application is properly configured

### 10. Quick Fix Checklist

- [ ] APP_KEY is set in `.env`
- [ ] OUTLOOK_ACCESS_TOKEN is set and valid
- [ ] VOODOOSMS_API_KEY is set and valid
- [ ] All services are registered in AppServiceProvider
- [ ] Laravel cache is cleared
- [ ] Application is restarted
- [ ] Network connectivity is working
- [ ] API credentials are correct 