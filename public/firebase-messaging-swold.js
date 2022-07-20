// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');


/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    // apiKey: "AIzaSyClFrparxwgA7_eEXCOw2GxEoL_7mN3laU",
    // authDomain: "mynotification-1e928.firebaseapp.com",
    // projectId: "mynotification-1e928",
    // storageBucket: "mynotification-1e928.appspot.com",
    // messagingSenderId: "878266863701",
    // appId: "1:878266863701:web:00ae2cd21328fff1c4c4dc",
    // measurementId: "G-DQQX4VXPEL"
    
       apiKey: "{{ env('apiKey') }}",
    authDomain: "{{ env('authDomain') }}",
    projectId: "{{ env('projectId') }}",
    storageBucket: "{{ env('storageBucket') }}",
    messagingSenderId: "{{ env('messagingSenderId') }}",
    appId: "{{ env('appId') }}" ,
    measurementId: "{{ env('measurementId') }}" 

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