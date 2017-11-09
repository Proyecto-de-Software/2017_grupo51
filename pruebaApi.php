<?php

    $results = file_get_contents('https://grupo51.proyecto2017.linti.unlp.edu.ar/api/api-turnos.php/turnos/9-11-2017');
    echo 'Paso';
    $url = 'http://localhost/grupo51/turnos.php';
    $data = array('/turnos','otra');
    
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
    if ($result === FALSE) { /* Handle error */ }
    
    var_dump($result);