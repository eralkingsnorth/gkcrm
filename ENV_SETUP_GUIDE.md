# Complete .env Setup Guide

## Step-by-Step Instructions

### Step 1: Generate Laravel App Key

First, make sure you have a Laravel app key:

```bash
php artisan key:generate
```

This will create a 32-character base64 encoded key like: `base64:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890=`

### Step 2: Get Outlook Access Token

Run the token generator script:

```bash
php get_outlook_token.php
```

This will automatically get your Outlook access token and test it.

### Step 3: Copy the Complete Template

Copy the contents of `env_complete_notifications.txt` to your `.env` file, then replace the placeholder values:

```env
# Laravel App Configuration
APP_NAME="GKCRM"
APP_ENV=local
APP_KEY=base64:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890=  # Your generated key
APP_DEBUG=true
APP_URL=https://goldmanknightley.co.uk

# Database Configuration (update with your actual database details)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gkcrm
DB_USERNAME=root
DB_PASSWORD=

# Outlook API Configuration
OUTLOOK_API_URL=https://graph.microsoft.com/v1.0
OUTLOOK_ACCESS_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik5HVEZ2ZEstZnl0aEV1Q...  # From get_outlook_token.php
OUTLOOK_CLIENT_ID=4fe41997-efb4-4607-adfd-4158deedf78c
OUTLOOK_CLIENT_SECRET=uBd8Q~IdwEJsfgIW6JYJyaltDaNQcRuwruN7taiz
OUTLOOK_TENANT_ID=07f5987e-d351-4de4-9c7a-4525be53ff1a

# VoodooSMS Configuration
VOODOOSMS_API_URL=https://api.voodoosms.com
VOODOOSMS_API_KEY=5coEhAV8HLfTg5HmiWjkH5DY6qhdiNBerqiOq5NxGtGnoO
VOODOOSMS_FROM=Kingsnorth

# API Key for Client API
API_KEY=gkcrm_api_key_2024_secure_12345
```

### Step 4: Clear Laravel Cache

After updating your `.env` file:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 5: Test the Configuration

Run the debug script to verify everything is working:

```bash
php debug_notifications.php
```

## Required Keys Summary

### Essential Keys (Must Have):
- `APP_KEY` - Laravel encryption key
- `OUTLOOK_ACCESS_TOKEN` - For sending emails
- `VOODOOSMS_API_KEY` - For sending SMS
- `APP_URL` - Your application URL

### Already Configured Keys:
- `OUTLOOK_CLIENT_ID` - Already set
- `OUTLOOK_CLIENT_SECRET` - Already set  
- `OUTLOOK_TENANT_ID` - Already set
- `VOODOOSMS_API_KEY` - Already set
- `API_KEY` - Already set

### Optional Keys:
- Database configuration (if not already set)
- Mail configuration (if using Laravel's built-in mail)

## Quick Setup Commands

```bash
# 1. Generate app key
php artisan key:generate

# 2. Get Outlook token
php get_outlook_token.php

# 3. Clear cache
php artisan config:clear

# 4. Test everything
php debug_notifications.php
```

## Verification Checklist

After setup, verify these are working:

- [ ] `APP_KEY` is set and valid
- [ ] `OUTLOOK_ACCESS_TOKEN` is obtained and working
- [ ] `VOODOOSMS_API_KEY` is configured
- [ ] `APP_URL` points to your domain
- [ ] Laravel cache is cleared
- [ ] Debug script shows all services working

## Troubleshooting

### If `get_outlook_token.php` fails:
- Check your internet connection
- Verify the credentials are correct
- Try running it manually with cURL

### If debug script shows errors:
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Verify all environment variables are set
- Make sure cache is cleared

### If notifications still don't work:
- Run `php debug_notifications.php` and share the output
- Check the Laravel logs for specific error messages
- Test the APIs manually with cURL

## Expected Result

After proper setup, when you create a new client, you should see:

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