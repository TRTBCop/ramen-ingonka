// 캐시 이름
const CACHE_NAME = 'cache-v1';

// 캐싱할 파일
const FILES_TO_CACHE = [
  '/favicon.ico',
  '/favicon-72.png',
  '/favicon-144.png',
  '/favicon-512.png',
  '/offline.html'
];

// 상술한 파일 캐싱
self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(FILES_TO_CACHE)));
});

// CACHE_NAME이 변경되면 오래된 캐시 삭제
self.addEventListener('activate', (event) => {
  event.waitUntil(
      caches.keys().then((keyList) =>
          Promise.all(
              keyList.map((key) => {
                if (CACHE_NAME !== key) return caches.delete(key);
              }),
          ),
      ),
  );
});

//fetch : web resource에 접근하기 위해 행해지는 모든 resquest action
self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        // First, try to use the navigation preload response if it's supported.
        const preloadResponse = await event.preloadResponse;
        if (preloadResponse) {
          return preloadResponse;
        }

        const networkResponse = await fetch(event.request);
        return networkResponse;
      } catch (error) {
        console.log('Fetch failed; returning offline page instead.', error);

        const cache = await caches.open(CACHE_NAME);
        const cachedResponse = await cache.match('/offline.html');
        return cachedResponse;
      }
    })());
  }
});
