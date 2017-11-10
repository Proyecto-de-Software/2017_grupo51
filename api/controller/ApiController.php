<?php

 class ApiController{
    private static $instance;
        
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function turnos($fecha){
        //Retorna los orarios de los turnos libres para una fecha dada
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
                $arreglo['error'] = 'Ya paso esta fecha.';
                $retorno = json_encode($arreglo);
            }
        }else{
            $arreglo['error'] = 'Fecha ingresada invalida.';
            $retorno = json_encode($arreglo);
        }
        return $retorno;
    }
    
    private function isDate($string) {
        //Comprueba que el string ingresado coincida con una fecha valida
        $matches = array();
        $pattern = '/^([0-9]{1,2})\\-([0-9]{1,2})\\-([0-9]{4})$/';
        if (!preg_match($pattern, $string, $matches)) return false;
        if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
        return true;
    }
    
    private function esFechaValida($unaFecha){
        //Valida la fecha ingresada no haya pasado todavia o sea la actual
        $fechaActual = date('Y').'-'.date("m").'-'.date("d");
        if($unaFecha >= $fechaActual){
            return true;
        }else{
            return false;
        }
    }
    
    private function horarios(){
        //Retorna una arreglo con los horarios de atencion en el hospital; turnos cada media hora, de 8 hs a 20 hs
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
                $arregloHorarios[$pos] = $hora.':'.$minutos;
                $pos++;
            }
        }
        return $arregloHorarios;
    }
    
    private function obtenerHorariosLibres($arregloDeHorarios,$consulta){
        //A partir de los turnos almacenados, se retorna la lista de horarios libres
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
    
    public function reservarTurno(){
        if($this->seIngresaronDatos()){
            $dni = $_POST['dni'];
            if(is_numeric($dni)){
                $fecha = $_POST['fecha'];
                if($this->isDate($fecha)){
                    $fechaFinal = date("Y-m-d", strtotime($fecha));
                    if($this->esFechaValida($fechaFinal)){
                        $horario = $_POST['hora'];
                        if($this->esHorarioValido($horario)){
                            $consulta = ApiModel::getInstance()->existeTurnoDado($fechaFinal,$horario);
                            if(count($consulta) == 0){
                                ApiModel::getInstance()->reservarTurno($fechaFinal,$horario,$dni);
                                $arreglo['error'] = 'Su turno para la fecha '.$fecha.' en el horario '.$horario.' hs, a sido reservado correctamente.';
                            }else{
                                $arreglo['error'] = 'Existe turno dado en la fecha y horario ingresado.';
                            }
                        }else{
                            $arreglo['error'] = 'Ingreso un horario invalido.';
                        }
                    }else{
                        $arreglo['error'] = 'Ya paso esta fecha.';
                    }
                }else{
                    $arreglo['error'] = 'Fecha ingresada invalida.';
                }
            }else{
                $arreglo['error'] = 'Ingreso un dni invalido.';
            }
        }else{
            $arreglo['error'] = 'Faltan datos.';
        }
        $retorno = json_encode($arreglo);
        return $retorno;
    }
    
    private function seIngresaronDatos(){
        //Retorna si las variables post necesarias fueron seteadas.
        return ( (isset($_POST['dni'])) && (isset($_POST['fecha'])) && (isset($_POST['hora'])) );
    }
    
    private function esHorarioValido($horario){
        //Valida que el horario sea valido (No permite que en la parte de minutos e ingrese numeros distintos de 00 o 30)
        $pattern="/^([0][89]|[1][0-9])[\:]([03][0])$/";
        if(preg_match($pattern,$horario)){
            return true;
        }
        return false;
    }
 }
