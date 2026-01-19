# Swagger API Documentation Implementation Guide

## 🚀 Overview
This project uses a **Hybrid Standalone approach** for API documentation. Instead of relying solely on the `l5-swagger` package's automatic generation (which proved unstable with routing/versions), we implemented a robust, static-file based solution that guarantees availability and easy maintenance.

### Key Components
1.  **OpenAPI Specification:** `storage/api-docs/api-docs.json` (The "Source of Truth")
2.  **Standalone UI:** `public/api-docs.html` (The "Viewer")
3.  **Routing:** Custom routes in `routes/web.php` to serve the spec and UI.
4.  **Security:** CSRF exemptions in `bootstrap/app.php` to allow "Try it out" functionality.

---

## 📂 File Structure

| File Path | Purpose |
| :--- | :--- |
| **`storage/api-docs/api-docs.json`** | **MAIN FILE.** Contains all API definitions, endpoints, schemas, and examples. Edit this to add/change documentation. |
| `public/api-docs.html` | A lightweight HTML wrapper that loads the Swagger UI assets from CDN and fetches your JSON spec. |
| `public/docs/api-docs.json` | A public copy of the spec file accessible by the browser (automatically updated via copy command). |
| `routes/web.php` | Defines `/api/documentation` and `/docs/api-docs.json` routes. |
| `bootstrap/app.php` | Configures CSRF exemptions so API testing works. |

---

## �️ How to Add New APIs

To document a new endpoint (e.g., if you add a `POST /new-feature`), you **do not** need to write PHP annotations. Simply edit the JSON file directly:

1.  Open **`storage/api-docs/api-docs.json`**.
2.  Find the `"paths": { ... }` section.
3.  Add your new endpoint. Example:

```json
"/new-feature": {
  "post": {
    "tags": ["New Feature"],
    "summary": "Description of what it does",
    "responses": {
      "200": {
        "description": "Success"
      }
    }
  }
}
```

4.  **Important:** After editing, you must copy the file to public so the browser sees the changes:
    ```bash
    copy storage\api-docs\api-docs.json public\docs\api-docs.json
    ```
    *(Or simply edit `public/docs/api-docs.json` directly if you prefer, but keep a backup).*

---

## 🔧 Configuration Details

### 1. Routing (`routes/web.php`)
We manually serve the UI and the JSON file to assume full control over headers and paths.

```php
// Serve the API JSON spec
Route::get('/docs/api-docs.json', function () {
    return response()->file(public_path('docs/api-docs.json'), [
        'Content-Type' => 'application/json'
    ]);
});

// Serve the UI
Route::get('/api/documentation', function () {
    return response()->file(public_path('api-docs.html'));
});
```

### 2. CORS Fix (`api-docs.json`)
To ensure the API works on both `localhost:8000` and `127.0.0.1:8000`, we set the server URL to be relative:

```json
"servers": [
  {
    "url": "/",
    "description": "Default Server (Relative)"
  }
]
```

### 3. CSRF Exaltation (`bootstrap/app.php`)
By default, Laravel blocks POST requests coming from external pages (like Swagger UI) due to missing CSRF tokens. We exempted API routes to allow testing:

```php
// In withMiddleware part of app.php
$middleware->validateCsrfTokens(except: [
    'chatbot/*',
    'payment/*',
    'wishlist/*',
]);
```

---

## ✅ Documented Endpoints

The following modules are fully documented:

*   **Analytics:** `GET /api/analytics/dashboard-stats`
*   **Chatbot:** `POST /chatbot/message`, `GET /chatbot/history`...
*   **Payments:** `POST /payment/create-order`, `POST /payment/verify`
*   **Wishlist:** `POST /wishlist/toggle`, `GET /wishlist/check/{id}`
*   **Locations:** `GET /get-cities/{country}`

## 🔗 Access Links

*   **Documentation UI:** [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)
*   **JSON Specification:** [http://127.0.0.1:8000/docs/api-docs.json](http://127.0.0.1:8000/docs/api-docs.json)
