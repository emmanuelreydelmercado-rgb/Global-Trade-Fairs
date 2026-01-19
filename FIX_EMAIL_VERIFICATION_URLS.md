# Fix Email Verification URLs in Production

## Problem
Email verification links are pointing to `127.0.0.1` instead of the production URL.

## Root Cause
The `APP_URL` environment variable in Render is set to localhost instead of the production domain.

## Solution

### Step 1: Update Environment Variables on Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Select your **Global Trade Fairs** web service
3. Go to **Environment** tab in the left sidebar
4. Find or Add the `APP_URL` variable:

```
Key: APP_URL
Value: https://global-trade-fairs.onrender.com
```

5. **Save Changes** (this will trigger an automatic redeploy)

### Step 2: Clear Configuration Cache

After the deployment completes, run these commands in the Render Shell:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

Or add them to your build script to run automatically.

### Step 3: Test Email Verification

1. Register a new user
2. Check the verification email
3. Click "Verify Email Address" button
4. It should now go to `https://global-trade-fairs.onrender.com/verify/...` instead of `127.0.0.1`

## Related Environment Variables to Check

Make sure these are also set correctly on Render:

| Variable | Production Value |
|----------|-----------------|
| `APP_URL` | `https://global-trade-fairs.onrender.com` |
| `ASSET_URL` | `https://global-trade-fairs.onrender.com` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |

## Why This Happens

Laravel uses `config('app.url')` (which reads from `APP_URL`) to generate:
- Email verification links
- Password reset links
- Asset URLs in emails
- Any `url()` or `route()` helpers in email templates

If `APP_URL` is wrong, all these URLs will be incorrect.

## Prevention

Always ensure your production environment variables match your actual domain before deploying.
