<?php

    $returnArray = true;
    $rawData = file_get_contents('php://input');
    $response = json_decode($rawData, $returnArray);
    $id_del_chat = $response['message']['chat']['id'];

    // Obtener comando (y sus posibles parametros)
    $regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


    $tmp = preg_match($regExp, $response['message']['text'], $aResults);
    
    if (isset($aResults[1])) {
        $cmd = trim($aResults[1]);
        $cmd_params = trim($aResults[2]);
    } else {
        $cmd = trim($response['message']['text']);
        $cmd_params = '';
    }
    
    //Armando la respuesta
    $msg = array();
    $msg['chat_id'] = $response['message']['chat']['id'];
    $msg['text'] = null;
    $msg['disable_web_page_preview'] = true;
    $msg['reply_to_message_id'] = $response['message']['message_id'];
    $msg['reply_markup'] = null;
    
    switch ($cmd) {
        case '/start':
            $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] .
                        " Usuario: " . $response['message']['from']['username'] . '!' . PHP_EOL;
            $msg['text'] .= '¿Como puedo ayudarte? /help';
            $msg['reply_to_message_id'] = null;
            break;

        case '/help':
            $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
            $msg['text'] .= '/start Inicializa el bot.' . PHP_EOL;
            $msg['text'] .= '/turnos dd-mm-aaaa Muestra los turnos disponibles del día.' . PHP_EOL;
            $msg['text'] .= '/reservar dni dd-mm-aaaa hh:mm Realiza la reserva del turno.' . PHP_EOL;
            $msg['text'] .= '/help Muestra esta ayuda.';
            $msg['reply_to_message_id'] = null;
            break;

        case '/reservar':
            $params = explode(' ', $cmd_params);
            $url = 'https://grupo51.proyecto2017.linti.unlp.edu.ar/api/api-turnos.php/turnos';
            $data = array('dni' => $params[0], 'fecha' => $params[1], 'hora' => $params[2]);
    
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
            $answer = json_decode($result);
            $msg['text'] = $answer->error;
            break;

        case '/turnos':
            if($cmd_params != NULL){
                $contents = file_get_contents("https://grupo51.proyecto2017.linti.unlp.edu.ar/final/public/turnos/".$cmd_params);
                $answer = json_decode($contents);
                if(isset($answer->error)){
                    $msg['text'] = $answer->error.PHP_EOL;
                }else{
                    $msg['text'] = 'Horarios libres para la fecha '.$cmd_params. PHP_EOL;
                    foreach($answer as $a){
                        $msg['text'] .= $a.' hs'.PHP_EOL;
                    }
            }}else{
                $msg['text'] = 'Debes ingresar una fecha del tipo DD-MM-AAAA';
            }
            break;
    }
    
    $url = 'https://api.telegram.org/bot603646984:AAHSKMvGTz688VPBRtIiZCEyzPvYcAnNc4c/sendMessage';

    $options = array(
        'http' => array(
           'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($msg)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    exit(0);