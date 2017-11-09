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
            $answer = $this->queryList("SELECT hora FROM turnos WHERE fecha=:fechaIngresada ORDER BY hora ASC", ['fechaIngresada' => $fecha]);
            return $answer;
        }
    }