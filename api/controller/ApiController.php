<?php

 class ApiController{
    private static $instance;
        
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function turnos($request,$fecha){
        if($this->isDate($fecha)){
            $fechaABuscar = date("Y-m-d", strtotime($fecha));
            if($this->esFechaValida($fechaABuscar)){
                $arregloDeHorarios = $this->horarios();
                $consulta = ApiModel::getInstance()->obtenerTurnos($fechaABuscar);
                if(count($consulta) > 0){
                    $arregloHorariosLibres = $this->obtenerHorariosLibres($arregloDeHorarios,$consulta);
                    $retorno = json_encode($arregloHorariosLibres);
                }else{
                    $retorno = json_encode($arregloDeHorarios);
                }
            }else{
                $retorno = json_encode(array('error'=>'Ya paso esta fecha'));
            }
        }else{
            $retorno = json_encode(array('error'=>'Fecha ingresada invalida'));
        }
        return $retorno;
    }
    
    private function isDate($string) {
        $matches = array();
        $pattern = '/^([0-9]{1,2})\\-([0-9]{1,2})\\-([0-9]{4})$/';
        if (!preg_match($pattern, $string, $matches)) return false;
        if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
        return true;
    }
    
    private function esFechaValida($unaFecha){
        $fechaActual = date('Y').'-'.date("m").'-'.date("d");
        if($unaFecha >= $fechaActual){
            return true;
        }else{
            return false;
        }
    }
    
    private function horarios(){
        $pos = 0;
        $arregloHorarios = array();
        $segundos = '00';
        for($i=8; $i<20; $i++){
            if($i < 10){
                $hora = '0'.strval($i);
            }else{
                $hora = strval($i);
            }
            for($j=0; $j<4; $j+=3){
                $minutos = strval($j).'0';
                $arregloHorarios[$pos] = $hora.':'.$minutos.':'.$segundos;
                $pos++;
            }
        }
        return $arregloHorarios;
    }
    
    private function obtenerHorariosLibres($arregloDeHorarios,$consulta){
        $comienzo = 0;
        $posActual = -1;
        $final = sizeof($consulta);
        foreach ($arregloDeHorarios as $horario) {
            $posActual++;
            if($comienzo <= $final){
                if($horario == $consulta[$comienzo][0]){
                    unset($arregloDeHorarios[$posActual]);
                    $comienzo++;
                    if($comienzo == $final){
                        break;
                    }
                }
            }
        }
        return $arregloDeHorarios;
    }
 }
