<?php
// functions.php

function sendSimplepushNotification($key, $title, $message) {
    $url = 'https://api.simplepush.io/send';
    $data = array(
        'key' => $key,
        'title' => $title,
        'msg' => $message
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Handle error
        return false;
    }
    
    return true;
}
?>