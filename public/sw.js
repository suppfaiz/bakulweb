// Disable console logs in production/public environment
if (self.location && !['localhost', '127.0.0.1', '[::1]'].includes(self.location.hostname)) {
  self.console.log = () => {};
  self.console.error = () => {};
  self.console.warn = () => {};
  self.console.info = () => {};
}

self.addEventListener('install', event => {
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  // Catch network failures to prevent "Uncaught (in promise) TypeError" console warnings
  event.respondWith(
    fetch(event.request).catch(err => {
      // Return a custom handled offline response
      return new Response('Offline/Network Error', {
        status: 480,
        statusText: 'Network Error'
      });
    })
  );
});
