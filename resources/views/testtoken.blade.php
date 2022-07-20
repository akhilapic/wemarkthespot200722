<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container" style="display: none;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card mt-3">
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ route('send.web-notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Message Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Message Body</label>
                            <textarea class="form-control" name="body"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<input type="text" name="token" value="" id="token"/>

<body onload="startFCM()">

</body>

<h5>Token View Call</h5>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    
    var firebaseConfig = {
    apiKey: "AIzaSyClFrparxwgA7_eEXCOw2GxEoL_7mN3laU",
    authDomain: "mynotification-1e928.firebaseapp.com",
    projectId: "mynotification-1e928",
    storageBucket: "mynotification-1e928.appspot.com",
    messagingSenderId: "878266863701",
    appId: "1:878266863701:web:00ae2cd21328fff1c4c4dc",
    measurementId: "G-DQQX4VXPEL"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
//    alert(messaging.getToken());
function startFCM() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })

            .then(function (response) {
                $("#token").val(response);
                alert(response);

            }).catch(function (error) {
      //          alert(error);
            });
    }   
function startFCM2() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })

            .then(function (response) {
                $("#token").val(response);
                alert(response);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
  //                      alert('Token stored.');
                    },
                    error: function (error) {
    //                    alert(error);
                    },
                });

            }).catch(function (error) {
      //          alert(error);
            });
    }

    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });

</script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<!-- <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    var firebaseConfig = {
    apiKey: "AIzaSyClFrparxwgA7_eEXCOw2GxEoL_7mN3laU",
    authDomain: "mynotification-1e928.firebaseapp.com",
    projectId: "mynotification-1e928",
    storageBucket: "mynotification-1e928.appspot.com",
    messagingSenderId: "878266863701",
    appId: "1:878266863701:web:00ae2cd21328fff1c4c4dc",
    measurementId: "G-DQQX4VXPEL"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
        messaging.requestPermission().then(function () {
                return messaging.getToken()
            })
            .then(function (response) {

            }).catch(function (error) {
        });
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });

</script> -->