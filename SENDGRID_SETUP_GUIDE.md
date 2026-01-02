# 📧 SendGrid Email Setup Guide

Complete guide to set up SendGrid for email verification on Render.

---

## 🎯 **Step 1: Sign Up for SendGrid**

1. Go to **https://signup.sendgrid.com/**
2. Click **"Start for Free"**
3. Fill in your details:
   - Email (use a real email you can access)
   - Password
   - Click **"Create Account"**
4. **Verify your email** (check inbox/spam)
5. Complete the onboarding questions:
   - Role: Developer
   - Company size: Personal or small team
   - Email purpose: Transactional emails
   - Primary language: PHP

---

## 🔑 **Step 2: Create SendGrid API Key**

1. After logging in, you'll be on the SendGrid dashboard
2. Click **"Settings"** in the left sidebar
3. Click **"API Keys"**
4. Click **"Create API Key"** button (top right)
5. Configure:
   - **API Key Name:** `Laravel-Global-Trade-Fairs`
   - **API Key Permissions:** Choose **"Full Access"** or **"Restricted Access"** → Check **"Mail Send"**
6. Click **"Create & View"**
7. **COPY THE API KEY IMMEDIATELY!** (You can only see it once)
   - It looks like: `SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`
8. Save it somewhere safe temporarily

---

## 📦 **Step 3: Install SendGrid PHP Package**

Run this command locally:

```bash
composer require sendgrid/sendgrid
```

---

## ⚙️ **Step 4: Configure Laravel for SendGrid**

### **A. Update `.env` (Local Testing)**

Add these to your local `.env` file:

```env
MAIL_MAILER=sendgrid
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

SENDGRID_API_KEY=SG.your-actual-api-key-here
```

Replace:
- `noreply@yourdomain.com` with any email you want (it's the "from" address)
- `SG.your-actual-api-key-here` with your actual SendGrid API key

### **B. Update `config/mail.php`**

Add SendGrid mailer configuration. This should already exist, but verify:

File: `config/mail.php`

Look for the `mailers` array and check if `sendgrid` exists. If not, we'll add it.

---

## 🚀 **Step 5: Add SendGrid Custom Mailer**

Since Laravel doesn't have built-in SendGrid support, we need to add it:

### **Create Service Provider**

We'll configure SendGrid to work with Laravel's mail system.

---

## 🌐 **Step 6: Configure Render Environment Variables**

1. Go to **Render Dashboard**
2. Click your service
3. Click **"Environment"** tab
4. Add these variables:

| Key | Value |
|-----|-------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.sendgrid.net` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `apikey` |
| `MAIL_PASSWORD` | `SG.your-sendgrid-api-key` |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` |
| `MAIL_FROM_NAME` | `Global Trade Fairs` |

**Replace:**
- `SG.your-sendgrid-api-key` with your actual API key
- `noreply@yourdomain.com` with your desired sender email

5. Click **"Save Changes"**

---

## ✅ **Step 7: Verify SendGrid Sender**

SendGrid requires sender verification:

1. Go to SendGrid Dashboard
2. **Settings** → **Sender Authentication**
3. Click **"Verify a Single Sender"**
4. Fill in:
   - **From Name:** Global Trade Fairs
   - **From Email:** noreply@yourdomain.com (or any email you control)
   - **Reply To:** your-real-email@gmail.com
   - Address details (can be approximate)
5. Click **"Create"**
6. **Check your email** and click verification link
7. Once verified, SendGrid can send from this address

---

## 🧪 **Step 8: Test Locally**

1. Run your Laravel app locally: `php artisan serve`
2. Try to register a new user
3. Check if email verification is sent
4. Check SendGrid dashboard → **Activity** to see sent emails

---

## 🚀 **Step 9: Deploy to Render**

1. Commit changes:
   ```bash
   git add composer.json composer.lock
   git commit -m "Add SendGrid email support"
   git push origin main
   ```

2. Deploy on Render:
   - Render Dashboard → Manual Deploy → Deploy latest commit

3. Wait for deployment (~5-7 minutes)

---

## 📧 **Step 10: Test on Render**

1. Visit your Render URL
2. Register a new user
3. Check your email for verification link
4. Check SendGrid dashboard to confirm email was sent

---

## ⚠️ **Important Notes**

### **SendGrid Free Tier Limits:**
- ✅ 100 emails/day
- ✅ Forever free
- ✅ Good for small apps

### **Common Issues:**

**Issue: "Mailer sendgrid not supported"**
- Make sure `MAIL_MAILER=smtp` (not `sendgrid`)
- SendGrid works through SMTP protocol

**Issue: "535 Authentication failed"**
- Double-check API key is correct
- Make sure `MAIL_USERNAME=apikey` (literally the word "apikey")
- `MAIL_PASSWORD` should be your actual API key

**Issue: Emails not arriving**
- Check spam folder
- Verify sender in SendGrid dashboard
- Check SendGrid Activity tab for errors

---

## 🔍 **Troubleshooting**

### Check SendGrid Activity:
1. SendGrid Dashboard
2. **Activity** tab
3. See all sent/failed emails
4. View error messages if any

### Test Command:
```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('your-email@gmail.com')->subject('Test'); });
```

---

## ✅ **Success Checklist**

- [ ] SendGrid account created
- [ ] Email verified
- [ ] API key created and saved
- [ ] Sender email verified in SendGrid
- [ ] `composer require sendgrid/sendgrid` installed
- [ ] Local `.env` updated
- [ ] Tested locally
- [ ] Render environment variables added
- [ ] Code pushed to GitHub
- [ ] Deployed on Render
- [ ] Tested on Render
- [ ] Email verification works! 🎉

---

## 📊 **What You Get:**

✅ **Professional emails** from your domain  
✅ **Verified sender** (looks legitimate)  
✅ **Email tracking** in SendGrid dashboard  
✅ **100/day free** emails  
✅ **Works on Render** (no port blocking issues)

---

**Need help?** Follow each step carefully and let me know where you get stuck!

**Last Updated:** 2025-12-30  
**Status:** Ready to set up  
**Time Required:** ~10 minutes
