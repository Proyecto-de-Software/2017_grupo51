<?php

    
    $url = 'https://grupo51.proyecto2017.linti.unlp.edu.ar/api/api-turnos.php/turnos';
    $data = array('dni' => 77777, 'fecha' => '11-11-2017', 'hora' => '20:00');
    
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);