# Google OAuth Separated Login & Registration Flow

## Overview
The Google OAuth flow has been completely refactored to separate **Login** and **Registration** with email verification requirements.

---

## New Flow Diagram

### 🔐 Login Flow (Existing Users Only)
```
User clicks "Sign in with Google" on Login page
    ↓
Redirects to /auth/google/login
    ↓
Google OAuth authentication
    ↓
Callback to /auth/google/callback (intent: login)
    ↓
Check if user exists with this email
    ├─ ❌ No user found
    │   → Redirect to login with error: "No account found. Please create an account first."
    │
    ├─ ❌ User exists but email NOT verified
    │   → Redirect to login with error: "Please verify your email before logging in."
    │
    └─ ✅ User exists AND email verified
        → Log user in
        → Redirect to home
```

### 📝 Registration Flow (New Users)
```
User clicks "Sign up with Google" on Register page
    ↓
Redirects to /auth/google/register
    ↓
Google OAuth authentication
    ↓
Callback to /auth/google/callback (intent: register)
    ↓
Check if user exists with this email
    ├─ ❌ User already exists (verified)
    │   → Redirect to register with error: "Account exists. Please use login page."
    │
    ├─ ❌ User exists but not verified
    │   → Redirect to register with error: "Account exists but not verified. Check your email."
    │
    └─ ✅ No existing user
        → Create new user with email_verified_at = NULL
        → Download Google profile picture
        → Send verification email
        → Redirect to login with success: "Account created! Please verify your email."
        → User CANNOT login until email is verified
```

---

## What Changed

### 1. **GoogleController.php**
- ✅ Split into two redirect methods:
  - `redirectToGoogleLogin()` - For existing users
  - `redirectToGoogleRegister()` - For new users
- ✅ Updated callback handler with session-based intent tracking
- ✅ Separate logic for login vs registration:
  - `handleGoogleLogin()` - Validates existing verified users
  - `handleGoogleRegistration()` - Creates unverified users
- ✅ Triggers Laravel's `Registered` event to send verification emails

### 2. **Routes (web.php)**
```php
// OLD (Single route for both)
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);

// NEW (Separate routes)
Route::get('/auth/google/login', [GoogleController::class, 'redirectToGoogleLogin']);
Route::get('/auth/google/register', [GoogleController::class, 'redirectToGoogleRegister']);
```

### 3. **Login Page (login.blade.php)**
```html
<!-- OLD -->
<a href="{{ route('auth.google') }}">Sign in with Google</a>

<!-- NEW -->
<a href="{{ route('auth.google.login') }}">Sign in with Google</a>
```

### 4. **Register Page (register.blade.php)**
```html
<!-- OLD -->
<a href="{{ route('auth.google') }}">Sign up with Google</a>

<!-- NEW -->
<a href="{{ route('auth.google.register') }}">Sign up with Google</a>
```

---

## Security Improvements

### ✅ Before (Insecure)
- Any Google user could auto-create an account and login immediately
- No email verification required
- Users could bypass registration flow

### ✅ After (Secure)
- **Login requires**:
  1. Existing account in database
  2. Email verified (email_verified_at not null)
- **Registration**:
  1. Creates unverified account
  2. Sends verification email
  3. User MUST verify before login

---

## User Experience

### For New Users:
1. Click "Create Account" on login page
2. Click "Sign up with Google"
3. Authenticate with Google
4. See success message: **"Account created! Please check your email to verify."**
5. Check email and click verification link
6. Return to login page
7. Click "Sign in with Google"
8. Successfully logged in ✅

### For Existing Users:
1. Click "Sign in with Google" on login page
2. Authenticate with Google
3. **If not verified**: Error message to verify email
4. **If verified**: Successfully logged in ✅

### For Users Who Try Wrong Flow:
- **Existing user uses Register page**: 
  - ❌ "Account exists. Please use login page."
- **New user uses Login page**: 
  - ❌ "No account found. Please create an account first."

---

## Email Verification

The system uses Laravel's built-in email verification:

1. **Verification email sent when**:
   - User registers with traditional form
   - User registers with Google OAuth

2. **Email contains**:
   - Verification link with signed URL
   - Expires after configured time (default: 60 minutes)

3. **Verification handled by**:
   - Laravel's `VerificationController`
   - Routes in `routes/auth.php`

---

## Testing Checklist

### Test 1: New User Registration with Google
- [ ] Go to register page
- [ ] Click "Sign up with Google"
- [ ] Authenticate with Google
- [ ] Verify redirect to login with success message
- [ ] Check email for verification link
- [ ] Click verification link
- [ ] Try to login with Google
- [ ] Verify successful login

### Test 2: Existing Verified User Login
- [ ] Go to login page
- [ ] Click "Sign in with Google"
- [ ] Verify immediate login success

### Test 3: Unverified User Login Attempt
- [ ] Create account but don't verify
- [ ] Try to login with Google
- [ ] Verify error: "Please verify your email first"

### Test 4: Wrong Flow - Use Login for New User
- [ ] New Google account (not in database)
- [ ] Click "Sign in with Google" on login page
- [ ] Verify error: "No account found. Please create account."

### Test 5: Wrong Flow - Use Register for Existing User
- [ ] Existing verified account
- [ ] Click "Sign up with Google" on register page
- [ ] Verify error: "Account exists. Please use login page."

---

## Configuration Required

Ensure your `.env` has email configured for verification emails:

```env
MAIL_MAILER=sendgrid
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Files Modified

1. ✅ `app/Http/Controllers/Auth/GoogleController.php` - Complete refactor
2. ✅ `routes/web.php` - Separate Google OAuth routes
3. ✅ `resources/views/auth/login.blade.php` - Updated route
4. ✅ `resources/views/auth/register.blade.php` - Updated route

---

## Error Messages Summary

| Scenario | Page | Message |
|----------|------|---------|
| New user tries login | Login | "No account found with this email. Please create an account first." |
| Unverified user tries login | Login | "Please verify your email address before logging in. Check your inbox for the verification link." |
| Existing user tries register | Register | "An account with this email already exists. Please use the login page instead." |
| Unverified user tries register again | Register | "An account with this email exists but is not verified. Please check your email for the verification link." |
| Successful registration | Login | "Account created successfully! Please check your email to verify your account before logging in." |
| Successful login | Home | "Successfully logged in with Google!" |

---

## Benefits of This Implementation

✅ **Security**: No unauthorized access without verification  
✅ **User Control**: Clear separation of login vs registration  
✅ **Email Validation**: Ensures valid email addresses  
✅ **Better UX**: Clear error messages guide users  
✅ **Compliance**: Follows email verification best practices  
✅ **Scalability**: Easy to add more OAuth providers with same pattern  

---

## Next Steps

1. Test all scenarios locally
2. Commit changes to Git
3. Deploy to production
4. Monitor user feedback
5. Consider adding:
   - Resend verification email option
   - Rate limiting on OAuth endpoints
   - Session timeout warnings
