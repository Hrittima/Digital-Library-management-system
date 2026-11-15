const CACHE_NAME = 'open-shelf-v1';
const OFFLINE_URL = '/offline.html';
const PUBLIC_PAGES = new Set(['/', '/index.php', '/books.php', '/book.php']);
const APP_ASSETS = [
    OFFLINE_URL,
    '/site.webmanifest',
    '/library.css',
    '/style.css',
    '/css/bootstrap.min.css',
    '/css/icofont.css',
    '/css/normalize.css',
    '/css/theme.css',
    '/css/responsive.css',
    '/fonts/icofont7858.woff',
    '/images/favicon.png',
    '/images/slide/slide1.jpg'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(APP_ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    const request = event.request;
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith((async () => {
            if (PUBLIC_PAGES.has(url.pathname)) {
                try {
                    const response = await fetch(request);
                    if (response.ok) {
                        const cache = await caches.open(CACHE_NAME);
                        await cache.put(request, response.clone());
                    }
                    return response;
                } catch {
                    return (await caches.match(request)) || caches.match(OFFLINE_URL);
                }
            }

            try {
                return await fetch(request);
            } catch {
                return caches.match(OFFLINE_URL);
            }
        })());
        return;
    }

    if (['style', 'script', 'image', 'font'].includes(request.destination)) {
        event.respondWith((async () => {
            const cached = await caches.match(request);
            if (cached) {
                return cached;
            }
            const response = await fetch(request);
            if (response.ok) {
                const cache = await caches.open(CACHE_NAME);
                await cache.put(request, response.clone());
            }
            return response;
        })());
    }
});
