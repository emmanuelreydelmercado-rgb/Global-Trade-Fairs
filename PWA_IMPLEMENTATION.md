# 📱 PWA Implementation Guide for Global Trade Fairs

This document outlines the steps taken to convert the Laravel application into a Progressive Web App (PWA) that is installable and offline-capable.

---

## 1. App Identity (`public/manifest.json`)
We created the **Web App Manifest**, which tells the browser how the app should look and behave when installed.

*   **Name:** "Global Trade Fairs"
*   **Display:** "standalone" (Removes browser UI/URL bar)
*   **Theme Color:** `#1a73e8` (Matches your brand blue)
*   **Icons:** Linked to `favicon.png` for home screen presence.

```json
{
  "name": "Global Trade Fairs",
  "display": "standalone",
  "theme_color": "#1a73e8",
  ...
}
```

---

## 2. The Engine (`public/sw.js`)
We implemented a **Service Worker** to handle caching and offline capabilities.

*   **Strategy:** **Network-First, Cache-Fallback**.
*   **Behavior:**
    1.  Tries to fetch the latest page from the internet.
    2.  If successful, copies it to the cache.
    3.  If offline (internet fails), serves the cached version.
*   **Assets Cached:** Main pages, CSS, JS, and critical images.

---

## 3. Frontend Integration

### A. Linking the Files
In `resources/views/global-fairs.blade.php` and `login.blade.php`:

```html
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#1a73e8">
```

### B. Registering the Service Worker
We added a script to register the worker at the bottom of the main page:

```javascript
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
}
```

---

## 4. "Install App" Button Logic

### The Challenge
Browsers fire the `beforeinstallprompt` event **very quickly** (often before specific Vue/Alpine components load). If you miss it, you can't show the Install button.

### The Solution: Global Capture
We added a global script immediately after `<body>` to catch the event and save it:

```html
<script>
    window.deferredPrompt = null;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // Stop standard browser banner
        window.deferredPrompt = e; // Save for later use
    });
</script>
```

### The UI Components
We added an "Install App" button in two places:
1.  **Desktop:** Inside the `Settings` dropdown.
2.  **Mobile:** Inside the `Mobile Navigation` drawer.

**Logic (Alpine.js):**
```javascript
async installApp() {
    // 1. Identify valid prompt
    let prompt = window.deferredPrompt || this.installPrompt;
    if (!prompt) return;

    // 2. Trigger native prompt
    prompt.prompt();

    // 3. Handle result
    const result = await prompt.userChoice;
    console.log('User choice:', result.outcome);
}
```

---

## ✅ Result
*   **Desktop:** Installable via Chrome address bar or Settings menu.
*   **Mobile:** Installable via "Add to Home Screen" or the Green Install Button in the menu.
*   **Offline:** The app now loads even without an internet connection (if the page was visited previously).
