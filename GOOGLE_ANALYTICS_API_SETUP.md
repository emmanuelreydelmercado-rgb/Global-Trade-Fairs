# Google Analytics Data API Setup Guide

## 🎯 Objective
Enable real-time Google Analytics data display in your admin dashboard.

---

## 📋 Prerequisites
- Google Analytics account with property `G-ST2W0DTJYK`
- Google Cloud Platform account
- Admin access to both

---

## 🚀 Step-by-Step Setup

### **Step 1: Create Google Cloud Project**

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click **"Select a project"** → **"New Project"**
3. **Project name:** `Global Trade Fairs Analytics`
4. Click **"Create"**
5. Wait for project creation (takes ~30 seconds)

---

### **Step 2: Enable Google Analytics Data API**

1. In Google Cloud Console, go to **"APIs & Services"** → **"Library"**
2. Search for: **"Google Analytics Data API"**
3. Click on it
4. Click **"Enable"** button
5. Wait for API to be enabled

---

### **Step 3: Create Service Account**

1. Go to **"APIs & Services"** → **"Credentials"**
2. Click **"Create Credentials"** → **"Service Account"**
3. **Service account details:**
   - **Name:** `analytics-dashboard-service`
   - **ID:** (auto-generated)
   - **Description:** `Service account for fetching GA data`
4. Click **"Create and Continue"**
5. **Grant access:** Skip (click "Continue")
6. **Grant users access:** Skip (click "Done")

---

### **Step 4: Create Service Account Key (JSON)**

1. Click on the service account you just created
2. Go to **"Keys"** tab
3. Click **"Add Key"** → **"Create new key"**
4. Select **"JSON"** format
5. Click **"Create"**
6. **JSON file will download automatically** - Save it securely!

---

### **Step 5: Add Service Account to Google Analytics**

1. Open the downloaded JSON file
2. Copy the **"client_email"** value (looks like: `analytics-dashboard-service@project-id.iam.gserviceaccount.com`)
3. Go to [Google Analytics](https://analytics.google.com)
4. Click **Admin** (gear icon, bottom left)
5. In the **Property** column, click **"Property Access Management"**
6. Click **"+"** (top right) → **"Add users"**
7. **Email address:** Paste the service account email
8. **Role:** Select **"Viewer"**
9. Uncheck **"Notify new users by email"**
10. Click **"Add"**

---

### **Step 6: Get Your Property ID**

1. In Google Analytics, go to **Admin** → **Property Settings**
2. Copy the **Property ID** (looks like: `123456789`)
3. Save this - you'll need it!

---

### **Step 7: Configure Laravel Application**

#### **A. Save JSON Credentials File**

1. Rename the downloaded JSON file to: `google-analytics-credentials.json`
2. Move it to: `storage/app/google-analytics-credentials.json`
3. **IMPORTANT:** This file should NOT be committed to Git!

#### **B. Update `.env` File**

Add these lines to your `.env` file:

```env
# Google Analytics Data API
GOOGLE_ANALYTICS_PROPERTY_ID=YOUR_PROPERTY_ID_HERE
GOOGLE_ANALYTICS_CREDENTIALS_PATH=storage/app/google-analytics-credentials.json
```

Replace `YOUR_PROPERTY_ID_HERE` with your actual Property ID from Step 6.

#### **C. Update `.env.example`**

Add the same lines to `.env.example` (for documentation):

```env
# Google Analytics Data API
GOOGLE_ANALYTICS_PROPERTY_ID=123456789
GOOGLE_ANALYTICS_CREDENTIALS_PATH=storage/app/google-analytics-credentials.json
```

---

### **Step 8: Update `.gitignore`**

Make sure your `.gitignore` includes:

```
storage/app/google-analytics-credentials.json
```

This prevents accidentally committing your credentials!

---

## ✅ Verification

After setup, your dashboard will show:
- ✅ **Today's Visitors** - Real count
- ✅ **Active Users** - Live count (updates every 30 seconds)
- ✅ **Page Views Today** - Real count
- ✅ **Avg. Duration** - Real time

---

## 🔧 Troubleshooting

### **Error: "Permission denied"**
- Make sure service account email is added to GA with "Viewer" role
- Wait 5-10 minutes for permissions to propagate

### **Error: "Property not found"**
- Double-check your Property ID
- Make sure you're using the GA4 property ID, not UA

### **Error: "Credentials file not found"**
- Check file path in `.env`
- Make sure file is in `storage/app/` directory

### **Error: "API not enabled"**
- Go to Google Cloud Console
- Enable "Google Analytics Data API"
- Wait a few minutes

---

## 🚀 For Production (Render)

1. Upload `google-analytics-credentials.json` to Render
2. Add environment variables in Render:
   - `GOOGLE_ANALYTICS_PROPERTY_ID`
   - `GOOGLE_ANALYTICS_CREDENTIALS_PATH`
3. Redeploy application

---

## 📊 What Data is Fetched?

The dashboard fetches:
- **Active Users** (last 30 minutes)
- **Total Users** (today)
- **Page Views** (today)
- **Average Session Duration** (today)

Data refreshes automatically every 30 seconds!

---

## 🔒 Security Notes

- ✅ JSON credentials file is gitignored
- ✅ Service account has read-only access
- ✅ No sensitive data exposed to frontend
- ✅ API calls are server-side only

---

## 📚 Additional Resources

- [Google Analytics Data API Documentation](https://developers.google.com/analytics/devguides/reporting/data/v1)
- [Service Account Setup](https://cloud.google.com/iam/docs/service-accounts-create)
- [GA4 Property ID](https://support.google.com/analytics/answer/9539598)

---

**Need help?** Check the troubleshooting section or contact support!
