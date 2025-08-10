// هذه روابط النسخة الـ compat المتوافقة مع importScripts
importScripts('https://www.gstatic.com/firebasejs/9.6.11/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.11/firebase-messaging-compat.js');

// إعدادات Firebase
firebase.initializeApp({
    apiKey: "AIzaSyC9Bsp_V1BLRFtX5z985ebrdwuPVoygYO8",
    authDomain: "medical-3dbfb.firebaseapp.com",
    projectId: "medical-3dbfb",
    storageBucket: "medical-3dbfb.firebasestorage.app",
    messagingSenderId: "3861161428",
    appId: "1:3861161428:web:37c9514c82c5214ede2241",
    measurementId: "G-4Z61EGYPRK"
});

// الحصول على messaging instance
const messaging = firebase.messaging();

// استقبال الرسائل في الخلفية
messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/firebase-logo.png'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
