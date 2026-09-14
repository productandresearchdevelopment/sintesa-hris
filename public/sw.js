const CACHE_NAME = 'sintesa-hris-v6';
const urlsToCache = [
  '/css/mobile.css',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png',
  '/images/logo-sintesa.jpg'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function(cache) {
      return Promise.all(
        urlsToCache.map(function(url) {
          return fetch(url).then(function(response) {
            if (response.ok) {
              return cache.put(url, response);
            }
          }).catch(function(err) {
            console.log('SW cache skip:', url, err);
          });
        })
      );
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.filter(function(cacheName) {
          return cacheName !== CACHE_NAME;
        }).map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', function(event) {
  if (event.request.method !== 'GET') return;
  event.respondWith(
    fetch(event.request).catch(function() {
      return caches.match(event.request);
    })
  );
});
