<?php
  
  function send_notification_FCM($body) {
     
    
    $headers = [
      'Authorization: key=' . env('FCM_KEY'),
      'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    $rest = curl_exec($ch);

 
    if ($rest === false) {
      $result_noti = 0;
    } else {
        \App\Models\NotificationLog::create([
            'title' => $body['data']['title'],
            'type' => $body['data']['type'],
            'body' => $body['data']['body'] ,
            'task_id' => $body['data']['task'],
            'user_id' => $body['data']['user_id']
         ]);
      $result_noti = 1;
    }
    return $result_noti;
}
