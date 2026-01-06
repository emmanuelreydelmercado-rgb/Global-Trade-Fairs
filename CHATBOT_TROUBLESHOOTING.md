# Chatbot Technical Issues - Troubleshooting Guide

## 🚨 **Current Issue**

**Error Message Shown:**
```
I'm experiencing technical difficulties. Please try again 
later or contact support at support@globaltradefairs.com
```

**What This Means:**
The chatbot is catching an exception and returning the generic error message. This is a **backend error**, not a frontend issue.

---

## 🔍 **Most Likely Causes**

### **1. ⚠️ GEMINI_API_KEY Not Set in Render (90% Probability)**

**Problem:** The Gemini API key is missing from Render's environment variables.

**Symptoms:**
- Chatbot works locally but not on Render
- Error message: "experiencing technical difficulties"
- Logs show: "Gemini API Error" or "API key not found"

**Solution:**
1. Go to https://dashboard.render.com
2. Select your `global-trade-fairs` web service
3. Click **"Environment"** in the left sidebar
4. Click **"Add Environment Variable"**
5. Add these three variables:

```
Key: GEMINI_API_KEY
Value: [Your actual API key from .env file]

Key: GEMINI_MODEL
Value: gemini-1.5-flash

Key: CHATBOT_ENABLED
Value: true
```

6. Click **"Save Changes"**
7. Wait for automatic redeploy (~2-3 minutes)

---

### **2. Invalid or Expired API Key (5% Probability)**

**Problem:** The API key is incorrect or has been revoked.

**How to Check:**
1. Open your local `.env` file
2. Find the line: `GEMINI_API_KEY=...`
3. Copy the value
4. Go to https://aistudio.google.com/apikey
5. Verify the key exists and is active

**Solution:**
- If key is invalid, generate a new one
- Update both local `.env` and Render environment variables

---

### **3. Database Connection Issue (3% Probability)**

**Problem:** Render can't connect to Aiven database.

**How to Check:**
1. Go to Render dashboard → Logs
2. Look for errors like:
   - "Connection refused"
   - "Access denied for user"
   - "Unknown database"

**Solution:**
- Verify Aiven database credentials in Render environment variables
- Check Aiven database is running
- Test connection from MySQL Workbench

---

### **4. Gemini API Rate Limit (2% Probability)**

**Problem:** You've exceeded the free tier API quota.

**Symptoms:**
- Worked earlier, stopped working suddenly
- Error in logs: "429 Too Many Requests" or "Quota exceeded"

**Solution:**
- Wait for quota to reset (usually 24 hours)
- Upgrade to paid Gemini API plan
- Check quota at https://aistudio.google.com/

---

## 🛠️ **Step-by-Step Troubleshooting**

### **Step 1: Check Render Logs**

1. Go to https://dashboard.render.com
2. Select your `global-trade-fairs` service
3. Click **"Logs"** tab
4. Look for recent errors when chatbot was used
5. Search for keywords:
   - "Gemini API Error"
   - "API key"
   - "Exception"
   - "Error fetching events"

**What to Look For:**
```
[ERROR] Gemini API Error: API key not found
[ERROR] GuzzleHttp\Exception\ClientException: 401 Unauthorized
[ERROR] Error fetching events data: ...
```

---

### **Step 2: Verify Environment Variables**

**In Render Dashboard:**
1. Go to **Environment** tab
2. Verify these exist:

| Variable | Expected Value | Status |
|----------|---------------|--------|
| `GEMINI_API_KEY` | AIza... (starts with AIza) | ❓ Check |
| `GEMINI_MODEL` | gemini-1.5-flash | ❓ Check |
| `CHATBOT_ENABLED` | true | ❓ Check |
| `DB_CONNECTION` | mysql | ✅ Should exist |
| `DB_HOST` | mysql-383cd7ab... | ✅ Should exist |

**If GEMINI_API_KEY is missing → This is the problem!**

---

### **Step 3: Test Locally**

If it works locally but not on Render, it's definitely an environment variable issue.

**Test Local Chatbot:**
1. Open terminal in project directory
2. Run: `php artisan serve`
3. Open: http://localhost:8000
4. Test the chatbot

**If it works locally:**
- ✅ Code is correct
- ✅ Database connection works
- ❌ Render environment variables are missing

---

### **Step 4: Check API Key Format**

**Valid Gemini API Key Format:**
```
AIzaSyD_example_key_1234567890abcdefghijklmnop
```

**Characteristics:**
- Starts with `AIza`
- About 39 characters long
- Contains letters, numbers, underscores, hyphens

**Invalid Examples:**
- Empty string
- "your_api_key_here"
- Truncated key
- Key with extra spaces

---

## 📋 **Quick Diagnostic Checklist**

Run through this checklist:

- [ ] **Render Environment Variables**
  - [ ] GEMINI_API_KEY exists
  - [ ] GEMINI_API_KEY starts with "AIza"
  - [ ] GEMINI_MODEL is set to "gemini-1.5-flash"
  - [ ] CHATBOT_ENABLED is set to "true"

- [ ] **Render Deployment**
  - [ ] Latest code is deployed
  - [ ] Deployment status is "Live"
  - [ ] No deployment errors in logs

- [ ] **Database Connection**
  - [ ] Aiven database is running
  - [ ] DB credentials are correct in Render
  - [ ] Can see events on the website

- [ ] **API Key Validity**
  - [ ] Key exists in Google AI Studio
  - [ ] Key is not expired or revoked
  - [ ] Key has API access enabled

---

## 🔧 **How to Fix (Most Common Solution)**

### **Add GEMINI_API_KEY to Render:**

1. **Get Your API Key:**
   - Option A: Check your local `.env` file (line ~68)
   - Option B: Get from https://aistudio.google.com/apikey

2. **Add to Render:**
   ```
   Go to Render Dashboard
   → Select "global-trade-fairs"
   → Click "Environment"
   → Click "Add Environment Variable"
   → Key: GEMINI_API_KEY
   → Value: [paste your API key]
   → Click "Save Changes"
   ```

3. **Wait for Redeploy:**
   - Render will automatically redeploy
   - Wait ~2-3 minutes
   - Check deployment status

4. **Test Again:**
   - Refresh your website
   - Open chatbot
   - Ask: "What are the upcoming events?"
   - Should now work! ✅

---

## 📊 **Error Code Reference**

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "experiencing technical difficulties" | Generic error caught | Check Render logs for details |
| "API key not found" | GEMINI_API_KEY not set | Add to Render environment |
| "401 Unauthorized" | Invalid API key | Check key validity |
| "429 Too Many Requests" | Rate limit exceeded | Wait or upgrade plan |
| "Connection refused" | Database issue | Check Aiven connection |
| "Event data temporarily unavailable" | Database query failed | Check DB credentials |

---

## 🧪 **Testing After Fix**

Once you've added the API key to Render:

1. **Wait for Deployment**
   - Check Render dashboard
   - Wait for "Deploy live" status
   - Should take 2-3 minutes

2. **Clear Browser Cache**
   - Press Ctrl+Shift+Delete
   - Clear cached images and files
   - Or use Incognito mode

3. **Test Chatbot**
   - Open: https://global-trade-fairs.onrender.com
   - Click chatbot icon
   - Ask: "What are the upcoming events?"
   - Should get detailed list of events

4. **Test Specific Query**
   - Ask: "Tell me about the Tech Expo 2025"
   - Should get complete details with phone, email, etc.

---

## 📝 **Expected Behavior After Fix**

### **Before Fix:**
```
User: "What are the upcoming events?"
Chatbot: "I'm experiencing technical difficulties..."
```

### **After Fix:**
```
User: "What are the upcoming events?"
Chatbot: 
"═══════════════════════════════════════
🟢 UPCOMING EVENTS (12)
═══════════════════════════════════════

【1】 **Tech Expo 2025**
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📅 Date: 27 Dec 2025 (Saturday)
⏰ Time Until Event: 22 days
📍 Venue: BHARAT MANDAPAM
🏙️ City: New Delhi
🌍 Country: India
🏛️ Organizer: ORACLE
📞 Phone: +91-9876543210
📧 Email: tech@oracle.com
🔗 Registration: https://techexpo.com/register

[... more events ...]"
```

---

## 🆘 **Still Not Working?**

If you've added the API key and it still doesn't work:

1. **Check Render Logs Again**
   - Look for new error messages
   - Copy the exact error text

2. **Verify API Key is Correct**
   - Go to https://aistudio.google.com/apikey
   - Test the key with a simple API call

3. **Check Database**
   - Verify events exist: Go to Events page on website
   - Should see 38 events

4. **Contact Me**
   - Share the error message from Render logs
   - Share screenshot of Environment variables (hide the actual key value)
   - I can help debug further

---

## 📞 **Support Information**

**For Users:**
- Email: support@globaltradefairs.com
- Message: "Chatbot technical issue"

**For You (Developer):**
- Check Render logs for detailed errors
- Verify all environment variables are set
- Test locally first to isolate the issue

---

## ✅ **Summary**

**Most Likely Issue:** GEMINI_API_KEY not set in Render

**Quick Fix:**
1. Go to Render Dashboard → Environment
2. Add GEMINI_API_KEY with your actual API key
3. Save and wait for redeploy
4. Test chatbot again

**Expected Result:** Chatbot will provide detailed event information instead of error message

---

**Created:** 2026-01-05
**Status:** Awaiting environment variable configuration
**Next Step:** Add GEMINI_API_KEY to Render
