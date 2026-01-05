# 🤖 AI Chatbot Setup Instructions

## ✅ Installation Complete!

Your AI-powered chatbot has been successfully installed! Here's what was created:

### 📁 Files Created:
1. **Database Migrations**
   - `database/migrations/2026_01_05_132212_create_chat_conversations_table.php`
   - `database/migrations/2026_01_05_132213_create_chat_messages_table.php`

2. **Models**
   - `app/Models/ChatConversation.php`
   - `app/Models/ChatMessage.php`

3. **Services**
   - `app/Services/GeminiService.php` (AI integration)

4. **Controllers**
   - `app/Http/Controllers/ChatbotController.php`

5. **Frontend Assets**
   - `public/css/chatbot.css`
   - `public/js/chatbot.js`

6. **Blade Include**
   - `resources/views/partials/chatbot.blade.php`

7. **Configuration**
   - Updated `config/services.php`
   - Updated `.env.example`
   - Added routes to `routes/web.php`

---

## 🔧 Setup Steps

### Step 1: Add API Key to .env

Open your `.env` file and add these lines:

```env
# Google Gemini API for Chatbot
GEMINI_API_KEY=AIzaSyD4okNGo5UHRsV2lUTn2f4EVs-HQ_V8Nzg
GEMINI_MODEL=gemini-pro
CHATBOT_ENABLED=true
```

### Step 2: Clear Config Cache

Run this command to refresh configuration:

```bash
php artisan config:clear
```

### Step 3: Add Chatbot to Your Pages

Add this line to any Blade template where you want the chatbot to appear:

```blade
@include('partials.chatbot')
```

**Recommended pages to add it:**
- `resources/views/global-fairs.blade.php` (homepage)
- `resources/views/tour-packages.blade.php`
- `resources/views/fair-details.blade.php`
- `resources/views/payment-details.blade.php`

**Example:**
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Global Trade Fairs</title>
    <!-- Your existing head content -->
</head>
<body>
    <!-- Your page content -->
    
    {{-- Add chatbot widget --}}
    @include('partials.chatbot')
</body>
</html>
```

---

## 🚀 Testing the Chatbot

### 1. Start Your Development Server

```bash
php artisan serve
```

### 2. Open Your Website

Visit: `http://localhost:8000`

### 3. Look for the Chat Button

You should see a blue floating chat button (💬) in the bottom-right corner.

### 4. Click and Test

Click the button and try these test messages:
- "What are your packages?"
- "Show me upcoming events"
- "How do I register?"
- "What is the pricing?"

---

## 🎨 Customization Options

### Change Chatbot Colors

Edit `public/css/chatbot.css` and modify these variables:

```css
/* Primary color (currently blue) */
background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);

/* Change to your brand color, e.g., green */
background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
```

### Modify System Prompt

Edit `app/Services/GeminiService.php` in the `getSystemPrompt()` method to customize how the bot responds.

### Add More Quick Actions

Edit `app/Http/Controllers/ChatbotController.php` in the `getQuickActions()` method.

---

## 📊 Features Included

✅ **AI-Powered Responses** - Uses Google Gemini API  
✅ **Conversation History** - Remembers context  
✅ **Session Management** - Tracks user sessions  
✅ **Quick Action Buttons** - Pre-defined questions  
✅ **Typing Indicator** - Shows when bot is thinking  
✅ **Mobile Responsive** - Works on all devices  
✅ **Beautiful UI** - Modern, animated design  
✅ **Database Storage** - Saves all conversations  
✅ **Intent Detection** - Understands user needs  

---

## 🔍 Troubleshooting

### Chatbot button doesn't appear?

1. Check if you added `@include('partials.chatbot')` to your Blade file
2. Clear browser cache (Ctrl+Shift+R)
3. Check browser console for JavaScript errors (F12)

### "API Error" message?

1. Verify your API key is correct in `.env`
2. Run `php artisan config:clear`
3. Check if you have internet connection (API requires it)

### Chatbot not responding?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database tables exist: `chat_conversations` and `chat_messages`
3. Test API key at: https://aistudio.google.com/

---

## 💰 Cost Monitoring

### Free Tier Limits:
- **60 requests per minute**
- **1,500 requests per day**
- **1 million tokens per month**

### Monitor Usage:
Visit: https://aistudio.google.com/app/apikey

---

## 📈 Next Steps (Optional Enhancements)

1. **Add to More Pages** - Include chatbot on all customer-facing pages
2. **Analytics Dashboard** - Track popular questions
3. **Admin Panel** - View all conversations
4. **Email Notifications** - Alert when users need help
5. **Multilingual Support** - Add Hindi/other languages
6. **Voice Input** - Add speech-to-text
7. **Live Agent Handoff** - Connect to human support

---

## 🎯 Quick Start Checklist

- [ ] Add API key to `.env`
- [ ] Run `php artisan config:clear`
- [ ] Add `@include('partials.chatbot')` to main pages
- [ ] Test on `http://localhost:8000`
- [ ] Try sending a message
- [ ] Check if responses work

---

## 📞 Support

If you encounter any issues:

1. Check `storage/logs/laravel.log` for errors
2. Verify all files were created correctly
3. Ensure Gemini API key is valid
4. Test with simple messages first

---

## 🎉 You're All Set!

Your AI chatbot is ready to help your customers 24/7!

**Enjoy your new AI assistant! 🤖✨**
