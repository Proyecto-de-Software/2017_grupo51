<?php

class Paciente{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function forPac(){
        //Muestra el formulario de registro de pacientes.
        $layout = IndexController::getInstance()->layout();
        Home::getInstance()->show('formPacientes.html.twig',$layout);
    }
    
    public function accesoPagPacientes(){
        //Accede a la seccion de pacientes.
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_seccion')){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = 'Bienvenido a la seccion de pacientes.';
            $layout['id_usuario'] = $_SESSION['id_usuario'];
            $layout['rol_nombre'] = $_SESSION['rolNombre'];
            Home::getInstance()->show('seccionPacientes.html.twig',$layout);
        }
    }
    
    public function listadoCompletoPacientes(){
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->obtenerPacientes();
            if(count($pacientes) == 0){
                    $parametros['mensaje'] = 'No hay pacientes registrados.';
                    $this->accesoAPaginaPacientes($parametros);
                }else{
                    $parametros['listaPacientes'] = $pacientes;
                    $parametros['mensaje'] = 'Listado de pacientes.';
                    $this->accesoAPaginaPacientes($parametros);
                }
        }
    }
    
    public function accesoAPaginaPacientes($arreglo){
        //Muestra la pagina con el listado de pacientes
        $layout = IndexController::getInstance()->layout();
        $layout['titulo'] = $arreglo['mensaje'];
        $layout['rol_nombre'] = $_SESSION['rolNombre'];
        if(isset($arreglo['listaPacientes'])){
            $layout['listaPacientes'] = $arreglo['listaPacientes'];
            $layout['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
        }elseif(isset($arreglo['pacienteCompleto'])){
            $layout['pacienteCompleto'] = $arreglo['pacienteCompleto'];
        }
        Home::getInstance()->show('seccionPacientes.html.twig',$layout);
    }
    
    public function verDatosCompletosPaciente($idPaciente){
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_show')){
            $paciente = PacienteModel::getInstance()->obtenerDatosCompletos($idPaciente);
            $arreglo = array(
                'mensaje' => 'Datos del paciente.',
                'pacienteCompleto' => $paciente,
            );
            $this->accesoAPaginaPacientes($arreglo);
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function eliminarPaciente($idPaciente){
        //Elimina el paciente pasado por parametro
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_destroy')){
            PacienteModel::getInstance()->eliminarPaciente($idPaciente);
            $this->listadoCompletoPacientes();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorNombre($nombrePaciente){
        //Busca pacientes por su nombre
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarNombrePaciente($nombrePaciente);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con nombre que contenga: '.$nombrePaciente;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes cuyo nombre contenga: '.$nombrePaciente;
            }
            $this->accesoAPaginaPacientes($parametros);
        }
    }
    
    public function buscarPorApellido($apellidoPaciente){
        //Busca pacientes por su apellido
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarApellidoPaciente($apellidoPaciente);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con apellido que contengan: '.$apellidoPaciente;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes cuyo apellido contenga: '.$apellidoPaciente;
            }
            $this->accesoAPaginaPacientes($parametros);
        }
    }
    
    public function buscarPorDocumento($numero,$doc){
        // Busca pacientes por tipo y numero de documento
        if(UserController::getInstance()->sePuedeAccederASeccion('paciente_index')){
            $pacientes = PacienteModel::getInstance()->buscarDocumentoPaciente($numero,$doc);
            if(count($pacientes) > 0){
                $parametros['mensaje'] = 'Pacientes con tipo de documento '.$doc. ' que contengan el número: '.$numero;
                $parametros['listaPacientes'] = $pacientes;
            }else{
                $parametros['mensaje'] = 'No hay pacientes con tipo de documento '.$doc. ' que contengan el número: '.$numero;
            }
            $this->accesoAPaginaPacientes($parametros);
    }
    }

    function ya_existe_paciente($numero_doc){
        $aux = PacienteValidation:: getInstance()->existePaciente($numero_doc);
        if (count($aux) == 0){
            return true;
        }else {
            return false;
        }
    }

    function crearTablaPaciente(){
        $arregloPac = array("0"=> $_POST["ApellidoP"], "1"=> $_POST["NombreP"],"2"=> $_POST["FecNacP"],"3"=> $_POST["GeneroP"] ,"4"=> $_POST["TipoDocP"], "5"=> $_POST["NroDocP"], "6"=> $_POST["DomicP"], "7"=> $_POST["TelefonoP"], "8"=> $_POST["ObraSocP"] );
        UserModel::getInstance()->insertarPaciente($arregloPac);
    }
}