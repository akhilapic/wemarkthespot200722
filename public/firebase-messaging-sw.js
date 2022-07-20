// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: 'AIzaSyDZL7XsO4_rE-Uc9akczO0_UDVBubFW-Ic',
    authDomain: "newtestingweb-7a4f2.firebaseapp.com",
    databaseURL: 'https://newtestingweb-7a4f2.firebaseio.com',
    projectId: 'newtestingweb-7a4f2',
    storageBucket: 'newtestingweb-7a4f2.appspot.com',
    messagingSenderId: '471183847824',
    appId: "1:471183847824:web:54047cb2f74e2d2c9bc315",
    measurementId: 'G-BJ55L0Y9WZ',
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});