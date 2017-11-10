<?php 
    require ('../vendor/autoload.php');
    require_once('controller/ApiController.php');
    require_once ('../models/PDORepository.php');
    require_once ('model/ApiModel.php');
    
    
    $app = new \Slim\App();
    
    $app->get("/turnos/{fecha}", function($request, $response, $args){
        return ApiController::getInstance()->turnos($args['fecha']);
    });
    
    $app->post('/turnos', function(){
       return ApiController::getInstance()->reservarTurno(); 
    });
    
    $app->run();