self.addEventListener('install', function(event) {
    self.skipWaiting();
  });
  
  self.addEventListener('activate', function(event) {
    // Limpeza de caches antigos, se necessário
  });
  
  self.addEventListener('fetch', function(event) {
    event.respondWith(
      fetch(event.request).catch(function() {
        // Você pode customizar uma resposta offline aqui
        return new Response('Você está offline.');
      })
    );
  });