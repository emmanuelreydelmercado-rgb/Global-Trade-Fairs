# WhatsApp Integration Guide

This guide explains how the WhatsApp notification system works for new events.

## Overview

When a new event uses the "Add Event" form (controlled by `InputBoxController`), a WhatsApp notification is sent to the administrator.

## Configuration

You need to add the following variables to your `.env` file:

```env
# WhatsApp Business API Config
# From Meta for Developers -> App -> WhatsApp -> API Setup
WHATSAPP_PHONE_NUMBER_ID=1049425901576268
WHATSAPP_ACCESS_TOKEN=your_long_access_token_here
WHATSAPP_RECIPIENT_PHONE_NUMBER=1234567890 
```
*Note: The recipient phone number must include the country code (e.g., `15551234567` for US).*

## Technical Details

### 1. `config/services.php`
Registers the WhatsApp service configuration:
```php
'whatsapp' => [
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'recipient_phone_number' => env('WHATSAPP_RECIPIENT_PHONE_NUMBER'),
],
```

### 2. `app/Services/WhatsAppService.php`
A dedicated service class that handles the API communication with Meta's Graph API.
- Endpoint: `https://graph.facebook.com/v22.0/{phone_number_id}/messages`
- Payload: Sends a text message with event details.
- Error Handling: Logs errors to `storage/logs/laravel.log`.

### 3. `app/Http/Controllers/InputBoxController.php`
The `addtable` method now instantiates `WhatsAppService` and calls `sendEventNotification` after successfully creating a `Form` (event).

## Troubleshooting

- **Message not received?**
  - Check `storage/logs/laravel.log` for errors.
  - Verify your Access Token is valid (Temporary tokens expire in 24 hours).
  - Ensure the recipient number is verified in the Meta App dashboard if you are in "Development" mode.
  - If using a Test Number, you can only send to your own verified number.
  - If the 24-hour window has passed, the text message might fail. You may need to use a Template message instead.

## Testing

1. Create a new event via the form.
2. Check if the message arrives.
3. Check `laravel.log` for success/failure messages.
