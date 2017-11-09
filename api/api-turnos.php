<?php 
    require ('../vendor/autoload.php');
    require_once('controller/ApiController.php');
    require_once ('../models/PDORepository.php');
    require_once ('model/ApiModel.php');
    
    
    $app = new \Slim\App();
    
    $app->get("/turnos/{fecha}", function($request, $response, $args){
        //return ApiController::getInstance()->turnos($args['fecha']);
        $var = ApiController::getInstance()->turnos($args['fecha']);
        $vars = json_decode($var);
        if(isset($vars['error'])){
            echo 'esissste';
        }
    });
    
    $app->run();