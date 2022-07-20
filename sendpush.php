<?php
   $url = 'https://fcm.googleapis.com/fcm/send';
        //    $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
        $FcmToken = array('eB1YIVecRkJ7pYdMCeM7LR:APA91bGfSIamvXH5ZxmIJ-jtruaMxThRKn6HJv1odrc8oYPQDSfwQ-xguo6zL_KmYUwg9_fG-bTMNEsJjT3VBX6cbSGJX4E_i30TNukn213rDBBWgMMKyIVHsCAsQCu1vCGBUo22ccH8');
        $serverKey = 'AAAAwMwmmOo:APA91bFpXKTWmS1QD72FhOQg-zq9KMT9tFSiRf_mXsyh42j68eZoWb2wwGhnbRxU4KXEgQzUKd7Ipyx9KzB7jrPxjtJUMTYCb_MYXRBYnO3VWDCgt1289GtOCNzaN0g2e0QS9kJUqKpe';
        $data = [
            "registration_ids" => $FcmToken,
            // "notification" => [
            //     "title" => $title,
            //     "body" => $body,
            // ],
            "data" => [
                "title" => 'test',
                "body" => 'test',
                'businessreview_id'=>'1',
                "type"=>'test',
                "reply_id"=>'test'
            ],
        
        ];
      //  dd($data);
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);
        print_r($result);die;
?>