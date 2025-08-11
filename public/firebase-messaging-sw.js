importScripts('https://www.gstatic.com/firebasejs/12.1.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.1.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyC9Bsp_V1BLRFtX5z985ebrdwuPVoygYO8",
    authDomain: "medical-3dbfb.firebaseapp.com",
    projectId: "medical-3dbfb",
    storageBucket: "medical-3dbfb.firebasestorage.app",
    messagingSenderId: "3861161428",
    appId: "1:3861161428:web:37c9514c82c5214ede2241",
    measurementId: "G-4Z61EGYPRK"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // استخدم بيانات الإشعار من payload.data
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        body: payload.data.body,
        data: {
            url: payload.data.url // نحتفظ بالرابط في data داخل الإشعار
        },
    };

    self.registration.showNotification(notificationTitle,
        notificationOptions);
});

// عند الضغط على الإشعار
self.addEventListener('notificationclick', function(event) {
  event.notification.close();

  let targetUrl = event.notification.data?.url;
  if (!targetUrl) {
    targetUrl = self.location.origin + '/dashboard';
  }

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(windowClients => {
      for (let client of windowClients) {
        if (client.url === targetUrl && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});



