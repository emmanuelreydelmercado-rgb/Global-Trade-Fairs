# Google OAuth Login Setup Guide

This guide will help you set up Google OAuth authentication for your Laravel application.

## ✅ Files Created/Modified

1. **Controller**: `app/Http/Controllers/Auth/GoogleController.php`
2. **Routes**: Updated `routes/web.php` with Google OAuth routes
3. **Migration**: `database/migrations/2026_01_10_add_google_id_to_users_table.php`
4. **Views**: Updated `login.blade.php` and `register.blade.php`

## 📋 Setup Steps

### Step 1: Install Laravel Socialite

Run this command in your terminal:

```bash
composer require laravel/socialite
```

### Step 2: Run the Migration

Add the `google_id` column to the users table:

```bash
php artisan migrate
```

### Step 3: Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **Google+ API**:
   - Go to "APIs & Services" → "Library"
   - Search for "Google+ API"
   - Click "Enable"

4. Create OAuth 2.0 credentials:
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "OAuth client ID"
   - Choose "Web application"
   - Set the authorized redirect URI:
     ```
     http://127.0.0.1:8000/auth/google/callback (for local)
     https://yourdomain.com/auth/google/callback (for production)
     ```
   - Click "Create"
   - Copy your **Client ID** and **Client Secret**

### Step 4: Update .env File

Add these lines to your `.env` file:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### Step 5: Add Configuration to config/services.php

Add this to the `config/services.php` file:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

## 🧪 Testing

### Local Testing
1. Start your local server: `php artisan serve`
2. Visit `http://127.0.0.1:8000/login`
3. Click "Sign in with Google"
4. Authorize the app with your Google account
5. You should be redirected back and logged in!

### Production Testing
1. Update your Google OAuth credentials with production URL
2. Update `.env` with production credentials
3. Test the flow on your live site

## 🔒 Security Notes

- Never commit your `.env` file to Git
- Keep your Google Client Secret private
- Use HTTPS in production
- Regularly rotate your credentials

## 📝 How It Works

1. User clicks "Sign in with Google"
2. They're redirected to Google's OAuth page
3. User authorizes the app
4. Google redirects back to `/auth/google/callback`
5. Our app receives user info from Google
6. We either:
   - Create a new user account (if email doesn't exist)
   - Log in existing user (if email already exists)
7. User is redirected to home page

## 🎨 Features

✅ Automatic user creation
✅ Profile picture download from Google
✅ Email verification bypass for Google users
✅ Works with both Login and Register pages
✅ Beautiful UI with Google branding
✅ Error handling

## 🐛 Troubleshooting

### "Class Socialite not found"
- Make sure you ran `composer require laravel/socialite`

### "Redirect URI mismatch"
- Check that the redirect URI in Google Console matches exactly
- Make sure there are no trailing slashes

### "Invalid credentials"
- Verify your Client ID and Secret in `.env`
- Make sure you enabled Google+ API

## 📚 Additional Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth Documentation](https://developers.google.com/identity/protocols/oauth2)

---

**Created**: January 10, 2026
**Feature**: Google OAuth Login Integration
