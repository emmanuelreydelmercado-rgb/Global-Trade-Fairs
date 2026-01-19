const CACHE_NAME = 'global-trade-fairs-v1';
const urlsToCache = [
    '/',
    '/favicon.png',
    '/manifest.json',
    '/css/chatbot.css',
    '/js/chatbot.js'
    // We will add more assets automatically as the user visits pages
];

// Install Event: Cache critical assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch Event: Network first, then Cache fallback
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // If we get a valid response, verify it
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }

                // Clone the response to cache it for later
                const responseToCache = response.clone();

                caches.open(CACHE_NAME)
                    .then(cache => {
                        // Don't cache API calls or non-GET requests if you want to be safe, 
                        // but for a simple PWA, caching visited pages is good.
                        if(event.request.method === 'GET' && !event.request.url.includes('/api/')) {
                            cache.put(event.request, responseToCache);
                        }
                    });

                return response;
            })
            .catch(() => {
                // If network fails (offline), try the cache
                return caches.match(event.request)
                    .then(response => {
                        if (response) {
                            return response;
                        }
                        // Optionally return an "offline.html" page here
                    });
            })
    );
});

// Activate Event: Clean up old caches
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
