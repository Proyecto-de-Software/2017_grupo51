<?php
    class ApiModel extends PDORepository{
        private static $instance;
        
        public static function getInstance(){
            if(!isset(self::$instance)){
                self::$instance = new self();
            }
            return self::$instance;
        }
        public function obtenerTurnos($fecha){
            $answer = $this->queryList("SELECT TIME_FORMAT(hora, '%H:%i') as horario FROM turnos WHERE fecha=:fechaIngresada ORDER BY hora ASC", ['fechaIngresada' => $fecha]);
            return $answer;
        }
    }